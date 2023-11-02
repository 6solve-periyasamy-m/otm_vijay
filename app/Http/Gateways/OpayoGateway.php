<?php

namespace App\Http\Gateways;

use App\Exceptions\UnauthorizedGatewayException;
use App\Http\Requests\Gateway\Opayo\WebhookRequest;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Order\Payment\PaymentIntention;
use Http;
use Log;

class OpayoGateway extends Gateway
{
    private string $success;
    private string $url;

    public function __construct(?string $success = null)
    {
        $this->success = $success ?? route('payment.gateway.stripe.success');
        $this->url = config('app.gateways.opayo.live', false) ? 'https://live.opayo.eu.elavon.com/gateway/service/vspserver-register.vsp' : 'https://sandbox.opayo.eu.elavon.com/gateway/service/vspserver-register.vsp';
    }

    public function checkout(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string
    {
        $amount = 0;
        $description = "";
        foreach ($items as $item) {
            $amount += $item->cost;
            $description .= "$item->name <br />";
        }
        $intention->amount = $amount;
        $intention->save();
        $data = [
            'VPSProtocol' => '4.00',
            'TxType' => 'PAYMENT',
            'Vendor' => config('app.gateways.opayo.vendor'),
            'VendorTxCode' => $intention->id,
            'Amount' => $amount,
            'Currency' => setting('system.currency', 'GBP'),
            'Description' => substr($description, 0, 100),
            'BillingSurname' => $customer->last_name,
            'BillingFirstnames' => $customer->first_name,
            'BillingAddress1' => $customer->billingAddress->address_line_1,
            'BillingCity' => $customer->billingAddress->town,
            'BillingCountry' => $customer->billingAddress->country->cca2,
            'BillingPostCode' => $customer->billingAddress->postcode,
            'DeliverySurname' => $customer->last_name,
            'DeliveryFirstnames' => $customer->first_name,
            'DeliveryAddress1' => $customer->homeAddress->address_line_1,
            'DeliveryAddress2' => $customer->homeAddress->address_line_2,
            'DeliveryCity' => $customer->homeAddress->town,
            'DeliveryCountry' => $customer->homeAddress->country->cca2,
            'DeliveryPostCode' => $customer->homeAddress->postcode,
            'InitiatedType' => 'CIT',
            'COFUsage' => 'FIRST',
            'NotificationURL' => route('api.opayo.webhook'),
        ];
        $response = Http::asForm()->post($this->url, $data);
        $body = str_to_map($response->body());
        if ($body['Status'] === 'OK' || $body['Status'] === 'OK REPEATED') {
            return $body['NextURL'];
        } else {
            Log::error("Failed to communicate with Opayo. Response:\n", $response->body());
            throw new UnauthorizedGatewayException('Failed to communicate with Opayo gateway');
        }
    }

    public function webhook(WebhookRequest $request)
    {
        if ($request->Status === 'OK') {
            // Opayo doesn't return an amount on success, so we'll need to pull from the payment intention
            $this->process($request->VendorTxCode, 0, now());
            return response("Status=OK\r\nRedirectURL=" . ($this->success), 200, ['Content-Type', 'text/plain']);
        }
        return response("Status=OK\r\nRedirectURL=" . (route('payment.gateway.opayo.failed')), 200, ['Content-Type', 'text/plain']);
    }

    public function failed()
    {
        // TODO: Implement actual page for failed payments
        return view('pages.payments.felloh.failed');
    }

    public function process(string $reference, float $amount, string $created = null): void
    {
        $intention = PaymentIntention::fetch($reference);
        if (!isset($intention)) return;
        $this->processIntention($intention, $intention->amount * 100 ?? 0, 'Opayo Gateway', $created);
    }
}
