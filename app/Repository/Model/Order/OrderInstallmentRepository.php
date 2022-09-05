<?php

namespace App\Repository\Model\Order;

use App\Models\Order\OrderInstallment;
use App\Repository\Abstracts\ModelRepository;

class OrderInstallmentRepository extends ModelRepository
{
    private OrderInstallment $installment;

    public function __construct(OrderInstallment $installment)
    {
        $this->installment = $installment;
    }

    public function getOldAmountPaid(): float
    {
        $order = $this->installment->order;
        $paid = sigfig(($order->total_adjustments * -1) + $order->paid - $order->calculated_deposit);
        foreach ($order->installments as $orderInstallment) {
            $paid = sigfig($paid - $orderInstallment->calculated_amount);
            if ($paid < 0 && $orderInstallment->id !== $this->installment->id) return 0;
            if ($orderInstallment->id == $this->installment->id) return $paid < 0 ? 0 : $this->installment->amount - $paid;
        }
        return $paid;
    }

    public function getAmountPaid(): float
    {
        $order = $this->installment->order;
        $paid = sigfig(($order->total_adjustments * -1) + $order->paid - $order->calculated_deposit);
        foreach ($order->installments as $installment) {
            if ($installment->id === $this->installment->id) {
                return min(max($paid, 0), $installment->calculated_amount);
            }
            $paid -= $installment->calculated_amount;
            if ($paid <= 0) return 0;
        }
        dd($paid);
    }

    public function isInstallmentPaid(): bool
    {
        return $this->getAmountPaid() == $this->installment->calculated_amount;
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
}
