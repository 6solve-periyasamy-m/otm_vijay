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

    public function isInstallmentPaid(): bool
    {
        $order = $this->installment->order;
        $paid = sigfig(($order->total_adjustments * -1) + $order->paid - $order->calculated_deposit);
        foreach ($order->installments as $orderInstallment) {
            $paid = sigfig($paid - $orderInstallment->calculated_amount);
            if ($paid < 0) return false;
            if ($orderInstallment->id == $this->installment->id) return true;
        }
        return $paid >= 0;
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