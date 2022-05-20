<?php

namespace App\Repository\Model\Order;

use App\Models\Order\OrderInstallment;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Facades\StringFormatter;

class OrderInstallmentRepository extends ModelRepository
{
    private OrderInstallment $installment;

    public function __construct(OrderInstallment $installment)
    {
        $this->installment = $installment;
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
        return StringFormatter::formatDate($this->installment->due_on) . ' - ' . StringFormatter::formatCurrency($this->installment->calculated_amount);
    }
}