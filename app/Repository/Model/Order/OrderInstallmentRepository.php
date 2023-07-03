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
