<?php

namespace App\Http\Gateways;

use App\Http\Requests\Gateway\Felloh\WebhookRequest;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Order\Payment\PaymentIntention;
use App\Models\System\GatewayPaymentLink;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Http;
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
        $description = substr($description, 0, -2);
        $order = Order::where('booking_reference', '=', $intention->reference)->first();
        $body = [
            'connectedAccountId' => config('app.gateways.felloh.connected'),
            'merchantRequestId' => $intention->reference,
            'amount' => $cost,
            'merchantName' => setting('company.name'),
            'logoUrl' => asset(setting('company.logo')),
            'paymentDescription' => substr($description, 0, 100),
            'successUrl' => $success ?? route('payment.gateway.stripe.success'),
            'cancelUrl' => route('payment.gateway.stripe.cancelled'),
            'isTemporaryRequestId' => isset($order),
            'currency' => setting('system.currency'),
            'customer' => [
                'name' => "$customer->first_name $customer->last_name",
                'email' => $customer->email_address,
                'address' => [
                    'addressLine1' => $customer->billingAddress->address_line_1,
                    'postCode' => $customer->billingAddress->postcode,
                ],
            ]
        ];
        $response = Http::withHeaders([
            'Account-ID' => config('app.gateways.felloh.account'),
            'Authorization' => 'Bearer ' . $this->token,
        ])->post($this->url . '/felloh-checkout-service/v1/checkout-payment', $body);
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
        $this->processIntention($intention, $amount, self::$GATEWAY, $created);
    }

    public function webhook(WebhookRequest $request)
    {
        if ($request->eventType === "PaymentCompleted") {
            $amount = $this->getTransactionAmount($request->transactionId);
            if ($amount === null) return;
            $this->process($request->transactionId, $amount, Carbon::createFromTimestamp($request->eventTimestamp));
        }
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
        $response = $this->makePostRequest(
            "{$this->url}/felloh-checkout-service/v1/token",
            [],
            ['clientId' => config('app.gateways.felloh.client'), 'clientSecret' => config('app.gateways.felloh.secret'),],
        false);
        return ['token' => $response->accessToken, 'expiry' => $response->expiryTime,];
    }

    private function makePostRequest(string $url, array $headers, array $bodyArray, bool $token = true)
    {
        if ($token) $this->renewToken();
        $client = new Client();
        $headers = [
            'Account-ID' => config('app.gateways.felloh.account'),
            ...$headers,
        ];
        if ($token) $headers['Authorization'] = 'Bearer ' . $this->token;
        try {
            $request = new Request('POST', $url, $headers, $this->encode_array($bodyArray));
            $res = $client->send($request);
            return json_decode($res->getBody());
        } catch (Exception $e) {
            Log::error($e);
        }
        return null;
    }

    private function getTransactionAmount(string $transactionId): ?float
    {
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
        $response = Http::withHeaders([
            'Account-ID' => config('app.gateways.felloh.account'),
            'Authorization' => 'Bearer ' . $this->token,
        ])->put($this->url . '/felloh-checkout-service/v1/checkout-payment', [
            'transactionId' => $transactionId,
            'oldMerchantRequestId' => $oldReference,
            'newMerchantRequestId' => $order->booking_reference,
        ]);
    }

    // Yes I know json_encode exists. Tell Felloh that
    private function encode_array(array $array): string
    {
        $body = "{";
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->encode_array($value);
            }
            $body .= "\"$key\": \"{$value}\",";
        }
        return substr($body, 0, -1) . "}";
    }
}
