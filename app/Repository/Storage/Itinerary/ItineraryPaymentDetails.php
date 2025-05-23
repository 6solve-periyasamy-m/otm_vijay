<?php

namespace App\Repository\Storage\Itinerary;

use App\Models\Location\Currency;

class ItineraryPaymentDetails
{
    public string|null $paymentDetails;

    /**
     * @param float $total
     * @param float|null $tax
     * @param float|null $commission
     * @param float|null $commissionPercent
     * @param float $cost
     * @param ItinerarySchedule[] $schedule
     * @param ItineraryPayment[] $payments
     */
    public function __construct(
        public float $total,
        public float|null $tax,
        public float|null $commission,
        public float|null $commissionPercent,
        public float $cost,
        public array $schedule,
        public array $payments,
        public Currency|null $currency = null,
    ) {
        $this->paymentDetails = setting('company.bank_transfer');
    }

    public function paid(): float
    {
        $paid = 0;
        foreach ($this->payments as $payment) { $paid += $payment->amount; }
        return $paid;
    }
}
