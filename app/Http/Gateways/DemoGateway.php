<?php

namespace App\Http\Gateways;

use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Order\Payment\PaymentIntention;

class DemoGateway extends Gateway
{
    private string $success;

    public function __construct(?string $success = null)
    {
        $this->success = $success ?? route('payment.gateway.stripe.success');
    }

    public function checkout(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string
    {
        $amount = 0;
        foreach ($items as $item) {
            $amount += $item->cost;
        }
        $this->process($intention->id, $amount*100);
        return $this->success;
    }

    public function process(string $reference, float $amount, string $created = null): void
    {
        $intention = PaymentIntention::fetch($reference);
        if (!isset($intention)) return;
        $this->processIntention($intention, $amount, 'Demo Gateway', $created);
    }
}
