<?php

namespace App\Http\Gateways;

use App\Exceptions\UnauthorizedGatewayException;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Order\Payment\PaymentIntention;
use App\Repository\Intention\PaymentIntentionRepository;
use Cache;
use Carbon\Carbon;
use Exception;
use Http;
use Illuminate\Http\Request;
use Log;

class AirwallexGateway extends Gateway
{
    private static string $GATEWAY = 'Airwallex';
    private string $token;
    private int $expiry;
    private string $url;

    public function __construct()
    {
        if (config('app.gateways.airwallex.live', false)) {
            $this->url = "https://api.airwallex.com/api/v1/";
        } else {
            $this->url = "https://api-demo.airwallex.com/api/v1/";
        }
        $this->renew();
    }

    public function renew(bool $force = false): void
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
            $cost += sigfig($item->cost);
            $description .= $item->name . ", ";
        }
        $intention->amount = $cost;
        $intention->save();
        return route('payment.gateway.airwallex.checkout', ['intent' => $intention->id,]);
    }

    public function showCheckout(Request $request)
    {
        try {
            $intent = PaymentIntention::find($request->intent);
            $aIntent = $this->getPaymentIntention($intent->amount, $intent);
            return view('pages.customer.payment.airwallex', ['intent' => $aIntent,]);
        } catch (UnauthorizedGatewayException $e) {
            return back()->withErrors(['msg' => 'That gateway has not been setup for use.']);
        }

    }

    /**
     * @throws UnauthorizedGatewayException
     */
    private function sendRequest(string $url, array $body): array
    {
        $this->renew();
        $request = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json',
        ])->post("{$this->url}{$url}", $body);
        if ($request->successful()) {
            return json_decode($request->body(), true);
        } else {
            Log::error("Failed to use Airwallex Gateway: " . $request->body());
            throw new UnauthorizedGatewayException('Could not authenticate with Airwallex Gateway');
        }
    }

    /**
     * @throws UnauthorizedGatewayException
     */
    public function getPaymentIntention(float $amount, PaymentIntention $intention): array
    {
        $data = $this->sendRequest('pa/payment_intents/create', [
            'amount' => $amount,
            'currency' => setting('system.currency', config('cashier.currency', 'gbp')),
            'merchant_order_id' => $intention->reference,
            'metadata' => [
                'intention_id' => $intention->id,
            ],
            'request_id' => $intention->id,
        ]);
        return ['id' => $data['id'], 'secret' => $data['client_secret'],];
    }

    public function process(string $reference, float $amount, string $created = null): void
    {
        $intention = PaymentIntention::find($reference);
        $this->processIntention($intention, $amount * 100, self::$GATEWAY, $created);
    }

    public function webhook(Request $request)
    {
        try {
            Log::channel('webhook')->info(self::$GATEWAY . " Gateway Webhook: ($request->status) {$request->json('id')}");
        } catch(Exception $e) {
            Log::error($e);
        }
        if ($request->json('name') === 'payment_link.paid' || $request->json('name') === 'payment_intent.succeeded') {
            $this->process($request->json('data.object.metadata.intention_id'), $request->json('data.object.amount'), $request->json('data.object.created_at'));
        }
    }

    /**
     * Fetch an up-to-date API token for use in requests
     * @param bool $force Should caching be skipped
     * @return array{token: string, expiry: int}
     * @throws UnauthorizedGatewayException Thrown if a 4xx error is returned from the API
     */
    private function getToken(bool $force = false): array
    {
        if (!$force) {
            $cached = Cache::get('airwallex.token');
            if ($cached !== null && isset($cached['token']) && ($cached['expiry'] ?? 0) > now()->unix()) {
                return $cached;
            }
        }
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-client-id' => config('app.gateways.airwallex.client'),
            'x-api-key' => config('app.gateways.airwallex.secret'),
        ])->post("{$this->url}/authentication/login",);
        if ($response->status() !== 201) {
            Log::error("Failed to use Airwallex Gateway: " . $response->body());
            throw new UnauthorizedGatewayException("Invalid Airwallex Information Provided");
        }
        $details = ['token' => $response->json('token'), 'expiry' => Carbon::parse($response->json('expires_at'))->unix()];
        Cache::put('airwallex.token', $details, $details['expiry']);
        return $details;
    }
}
