<?php

namespace App\Repository\Model\Order;

use App\Models\Order\OrderInstallment;
use App\Models\Order\Payment\Payment;
use App\Repository\Abstracts\ModelRepository;

class OrderInstallmentRepository extends ModelRepository
{
    private OrderInstallment $installment;

    public function __construct(OrderInstallment $installment)
    {
        $this->installment = $installment;
    }

    public function getAmountPaid(): float
    {
        return $this->installment->calculated_amount - $this->getRemainingAmount();
    }

    public function getRemainingAmount(): float
    {
        if (isset($this->installment->remaining)) {
            return $this->installment->remaining;
        }
        return $this->installment->order->repository->getInstallments()->firstWhere('id', '=', $this->installment->id)->remaining;
    }

    public function isInstallmentPaid(): bool
    {
        return $this->getAmountPaid() == $this->installment->calculated_amount;
    }

    public function getCoveringPayment(): Payment|null
    {
        $order = $this->installment->order;
        $totalOwed = $order->calculated_deposit + ($order->booking_fee ?? 0);
        foreach ($order->installments as $installment) {
            $totalOwed += $installment->calculated_amount;
            if ($installment->id === $this->installment->id) break;
        }
        // Account for refunds before calculating
        foreach ($order->payments as $payment) {
            if ($payment->amount < 0) $totalOwed -= $payment->amount;
        }

        foreach ($order->payments as $payment) {
            if ($payment->amount < 0) continue;
            $totalOwed -= $payment->amount;
            if ($totalOwed <= 0) return $payment;
        }
        return null;
    }

    public function get(): OrderInstallment
    {
        return $this->installment;
    }

    public function update(array $data): OrderInstallment
    {
        $this->installment->update($data);
        $this->save();
        return $this->installment;
    }

    public function save(): bool
    {
        return $this->installment->save();
    }

    public function delete(): bool
    {
        return $this->installment->delete();
    }

    public function isDeleted(): bool
    {
        return $this->installment->trashed();
    }

    public function __toString(): string
    {
        return f_date($this->installment->due_on) . ' - ' . f_currency($this->installment->calculated_amount);
    }

    public static function find($id): OrderInstallment|null
    {
        return OrderInstallment::find($id);
    }
}
