<?php

namespace App\Http\Gateways;

use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Order\Payment\PaymentIntention;
use Http;

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
            'NotificationURL' => route('api.log'),
        ];
        $response = Http::withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])->post($this->url, $data);
        dd($response, $response->body());
        return $success ?? $this->success;
    }

    public function process(string $reference, float $amount, string $created = null): void
    {
        $intention = PaymentIntention::fetch($reference);
        if (!isset($intention)) return;
        $this->processIntention($intention, $amount, 'Demo Gateway', $created);
    }
}
