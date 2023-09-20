<?php

namespace App\Http\Gateways;

use App\Exceptions\UnauthorizedGatewayException;
use App\Http\Requests\Gateway\Felloh\NewWebhookRequest;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Order\Payment\PaymentIntention;
use App\Models\System\GatewayPaymentLink;
use App\Repository\Interfaces\GeneratesFellohData;
use App\Repository\Model\Booking\BookingRepository;
use App\Repository\Model\Order\OrderRepository;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Log;

class NewFellohGateway extends Gateway
{
    private string $token;
    private int $expiry;
    private static string $GATEWAY = 'Felloh';
    private static bool $log = true;

    public function __construct()
    {
        $this->url = 'https://' . config('app.gateways.felloh.env', 'api') . '.felloh.com';
    }

    private function renew(bool $force = false)
    {
        if (now()->unix() > ($this->expiry ?? 0) || $force) {
            $token = $this->getToken();
            $this->token = $token['token'];
            $this->expiry = $token['expiry'];
        }
    }

    public function checkout(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string
    {
        $cost = 0;
        $description = "";
        foreach ($items as $item) {
            $cost += $item->cost;
            $description .= $item->name . ", ";
        }
        $description = preg_replace('/[^a-zA-Z0-9]/', '', substr($description, 0, -2)); // Anyone wondering: Remove the final comma, remove any non-alphanumeric characters
        /** @var OrderRepository|BookingRepository|GeneratesFellohData $order */
        if ($customer instanceof BookingTraveller) {
            $order = $customer->booking->repository;
        } else {
            $order = Order::where('booking_reference', '=', $intention->reference)->first()->repository;
        }
        // Since we'd plan to fetch the booking, then update, using update we can just fetch and update in a single call
        $fellohId = $this->updateFellohBooking($order);
        $response = Http::withHeaders($this->headers())
            ->put("{$this->url}/agent/payment-links", [
                'organisation' => config('app.gateways.felloh.organisation'),
                'customer_name' => "$customer->first_name $customer->last_name",
                'email' => $customer->email_address,
                'booking_id' => $fellohId,
                'amount' => $cost * 100,
                'description' => substr($description, 0, 99),
                'open_banking_enabled' => true,
                'card_enabled' => true,
            ]);
        $this->verifyStatus($response);
        $link = $response->json('data.id');
        GatewayPaymentLink::create([
            'gateway' => self::$GATEWAY,
            'payment_reference' => $link,
            'payment_intention_id' => $intention->id,
        ]);
        return "https://pay.felloh.com/{$link}";
    }

    /**
     * @throws UnauthorizedGatewayException
     */
    public function process(string $reference, float $amount, string $created = null): void
    {
        $intention = GatewayPaymentLink::get(self::$GATEWAY, $reference)?->intention;
        if (!isset($intention)) return;
        $booking = Booking::where('token', '=', $intention->reference)->first();
        $order = $this->processIntention($intention, $amount * 100, self::$GATEWAY, $created);
        if (isset($booking)) {
            $this->updateReference($reference, $order);
        }
    }


    // Webhook Handler

    /**
     * @throws UnauthorizedGatewayException
     */
    public function webhook(NewWebhookRequest $request): JsonResponse
    {
        self::$log && Log::info($request);
        try {
            Log::channel('webhook')->info(self::$GATEWAY . " Gateway Webhook: ($request->status) {$request->transaction['id']}");
        } catch(Exception $e) {
            Log::error($e);
        }
        if ($request->status === "COMPLETE") {
            if ($request->amount === null) return response()->json(['success' => true,]);
            $amount = sigfig($request->amount / 100);
            $this->process($request->payment_link['id'], $amount, Carbon::createFromTimestamp($request->completed_at));
        }
        return response()->json(['success' => true,]);
    }


    // API Calls

    /**
     * Fetch an up-to-date API token for use in requests
     * @return array{token: string, expiry: int}
     * @throws UnauthorizedGatewayException
     */
    private function getToken(): array
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post("{$this->url}/token", ['public_key' => config('app.gateways.felloh.public'), 'private_key' => config('app.gateways.felloh.private'),]);
        self::$log && Log::info($response->body());
        if ($response->status() !== 200) {
            throw new UnauthorizedGatewayException("Invalid Felloh Information Provided");
        }
        return ['token' => $response->json('data.token'), 'expiry' => $response->json('data.expiry_time')];
    }

    /**
     * Fetch a booking from Felloh, or generate a new one if it doesn't exist
     * @param GeneratesFellohData $order
     * @return string
     * @throws UnauthorizedGatewayException
     */
    private function getFellohBooking(GeneratesFellohData $order): string
    {
        $response = Http::withHeaders($this->headers())
            ->post("{$this->url}/agent/bookings", ['organization' => config('app.gateways.felloh.organisation'), 'booking_reference' => $order->getReference(),]);
        self::$log && Log::info($response->body());
        $this->verifyStatus($response);
        if (intval($response->json('meta.count')) < 1) {
            return $this->createFellohBooking($order);
        } else {
            return "" . $response->json("data[0].id");
        }
    }

    /**
     * Generate a new Booking on felloh's systems
     * @param GeneratesFellohData $order
     * @return string
     * @throws UnauthorizedGatewayException
     */
    private function createFellohBooking(GeneratesFellohData $order): string
    {
        $response = Http::withHeaders($this->headers())
            ->put("{$this->url}/agent/bookings", [
                'organisation' => config('app.gateways.felloh.organisation'),
                ...$order->getFellohData(),
        ]);
        self::$log && Log::info($response->body());
        $this->verifyStatus($response);
        return "" . $response->json('data.id');
    }

    /**
     * Update the details of a booking on felloh's systems with up-to-date details from our end
     * @param GeneratesFellohData $order
     * @param string|null $fellohId The known id of the order on felloh's system. Used if called in chain to prevent multiple accesses. If null, will fetch the ID first
     * @return string The felloh ID of the order
     * @throws UnauthorizedGatewayException
     */
    private function updateFellohBooking(GeneratesFellohData $order, string $fellohId = null): string
    {
        $fellohId = $fellohId ?? $this->getFellohBooking($order);
        $response = Http::withHeaders($this->headers())
            ->post("{$this->url}/agent/bookings/{$fellohId}", $order->getFellohData());
        self::$log && Log::info($response->body());
        $this->verifyStatus($response);
        return $fellohId;
    }

    /**
     * Update the remote booking reference when converting from a booking to an order internally
     * @param string $booking Felloh booking id to be updated
     * @param Order $order The converted order
     * @throws UnauthorizedGatewayException Thrown if an error occurs during the
     */
    private function updateReference(string $booking, Order $order): void
    {
        $response = Http::withHeaders($this->headers())
            ->put("{$this->url}/agent/bookings/{$booking}/update-reference", ['booking_reference' => $order->booking_reference,]);
        self::$log && Log::info($response->body());
        $this->verifyStatus($response);
    }

    private function headers(): array
    {
        $this->renew();
        return ['Content-Type' => 'application/json', 'Authorization' => "Bearer {$this->token}"];
    }

    /**
     * Verify the status code of the response
     * @throws UnauthorizedGatewayException
     */
    private function verifyStatus(PromiseInterface|Response $response): void
    {
        if ($response->status() === 200) {
            return;
        }
        if ($response->status() === 401) {
            throw new UnauthorizedGatewayException("Invalid Felloh Information Provided");
        }
        if ($response->status() === 401) {
            Log::error($response->body());
            throw new UnauthorizedGatewayException("Validation exception occurred in felloh gateway");
        }
    }
}