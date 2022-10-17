<?php

namespace App\Http\Gateways;

use App\Http\Requests\Gateway\Felloh\WebhookRequest;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Order\Payment\PaymentIntention;
use App\Models\System\GatewayPaymentLink;
use Carbon\Carbon;
use Exception;
use Http;
use Illuminate\Http\JsonResponse;
use Log;

class FellohGateway extends Gateway
{
    private string $token;
    private int $expiry;
    private static string $GATEWAY = 'Felloh';

    public function __construct()
    {
        $this->url = 'https://' . config('app.gateways.felloh.env', 'api') . '.felloh.org';
    }

    public function checkout(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string
    {
        $this->renewToken();
        $cost = 0;
        $description = "";
        foreach ($items as $item) {
            $cost += $item->cost;
            $description .= $item->name . ", ";
        }
        $description = preg_replace('/[^a-zA-Z0-9]/', '', substr($description, 0, -2));
        $order = Order::where('booking_reference', '=', $intention->reference)->first();
        $body = [
            'connectedAccountId' => config('app.gateways.felloh.connected'),
            'merchantRequestId' => substr($intention->reference, 0, 40),
            'amount' => $cost,
            'merchantName' => setting('company.name'),
            'logoUrl' => asset(setting('company.logo')),
            'paymentDescription' => substr($description, 0, 99),
            'successUrl' => $success ?? route('payment.gateway.stripe.success'),
            'cancelUrl' => route('payment.gateway.stripe.cancelled'),
            'isTemporaryRequestId' => isset($order),
            'currency' => setting('system.currency', 'GBP'),
            'customer' => [
                'name' => "$customer->first_name $customer->last_name",
                'email' => $customer->email_address,
                'address' => [
                    'addressLine1' => $customer->billingAddress->address_line_1,
                    'postCode' => $customer->billingAddress->postcode,
                ],
            ],
            'paymentStatusCallbackUrl' => route('api.felloh.webhook'),
            'allowedPaymentMethods' => 'CARD',
        ];
        $response = Http::withHeaders([
            'Account-ID' => config('app.gateways.felloh.account'),
            'Authorization' => 'Bearer ' . $this->token,
        ])->post($this->url . '/felloh-checkout-service/v1/checkout-payment', $body);
        if ($response->status() !== 201) {
            Log::error("Failed fetching felloh gateway: \n" . $response->body());
            return back()->getTargetUrl();
        }
        GatewayPaymentLink::create([
            'gateway' => self::$GATEWAY,
            'payment_reference' => $response->json('transactionId'),
            'payment_intention_id' => $intention->id,
        ]);
        return $response->json('paymentRedirectUrl');
    }

    public function process(string $reference, float $amount, string $created = null): void
    {
        $intention = GatewayPaymentLink::get(self::$GATEWAY, $reference)?->intention;
        if (!isset($intention)) return;
        $booking = Booking::where('token', '=', $intention->reference)->first();
        $order = $this->processIntention($intention, $amount * 100, self::$GATEWAY, $created);
        if (isset($booking)) {
            $this->updateMerchantRequestId($reference, $intention->reference, $order);
        }
    }

    public function webhook(WebhookRequest $request): JsonResponse
    {
        try {
            Log::channel('webhook')->info(self::$GATEWAY . " Gateway Webhook: ($request->eventType) $request->transactionId");
        } catch(Exception $e) {
            Log::error($e);
        }
        if ($request->eventType === " PaymentAuthorised"
            || $request->eventType === "PaymentReceived"
            || $request->eventType === "PaymentCompleted") {
            $amount = $this->getTransactionAmount($request->transactionId);
            if ($amount === null) response()->json(['success' => true,]);
            $this->process($request->transactionId, $amount, Carbon::createFromTimestamp($request->eventTimestamp/1000));
        }
        return response()->json(['success' => true,]);
    }

    private function renewToken(): void
    {
        if (!isset($this->expiry) || now()->unix() >= $this->expiry) {
            $token = $this->getApiToken();
            $this->token = $token['token'];
            $this->expiry = intval($token['expiry']);
        }
    }

    private function getApiToken(): array
    {
        $body = ['clientId' => config('app.gateways.felloh.client'), 'clientSecret' => config('app.gateways.felloh.secret'),];
        $response = Http::withHeaders([
            'Account-ID' => config('app.gateways.felloh.account'),
        ])->post($this->url . '/felloh-checkout-service/v1/token', $body);
        return ['token' => $response->json('accessToken'), 'expiry' => $response->json('expiryTime'),];
    }

    private function getTransactionAmount(string $transactionId): ?float
    {
        $this->renewToken();
        $response = Http::withHeaders([
            'Account-ID' => config('app.gateways.felloh.account'),
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->url . '/felloh-checkout-service/v1/checkout-payment/status/' . $transactionId);
        if ($response->status() !== 200) {
            return null;
        }
        return $response->json('amount');
    }

    private function updateMerchantRequestId(string $transactionId, string $oldReference, Order $order)
    {
        $this->renewToken();
        $response = Http::withHeaders([
            'Account-ID' => config('app.gateways.felloh.account'),
            'Authorization' => 'Bearer ' . $this->token,
        ])->put($this->url . '/felloh-checkout-service/v1/checkout-payment', [
            'connectedAccountId' => config('app.gateways.felloh.connected'),
            'transactionId' => $transactionId,
            'oldMerchantRequestId' => substr($oldReference, 0, 40),
            'newMerchantRequestId' => $order->booking_reference,
        ]);
    }
}
