<?php

namespace App\Http\Livewire\Admin\System\PaymentMethod;

use App\Http\Livewire\Abstract\ActionColumn;
use App\Http\Livewire\SendsEvents;
use App\Models\Order\Payment\PaymentMethod;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    use SendsEvents;

    public $name = "payment-method-table";

    public function builder()
    {
        return PaymentMethod::query();
    }

    public function columns()
    {
        return [
            Column::callback(['name', 'fee_percentage'], function ($name, $fee) {
                if ($fee !== 0.0 && empty($fee)) {
                    return "{$name} (No Fee)";
                }
                $fee = (float)$fee;
                return "{$name} ({$fee}%)";
            })
                ->label('Name')
                ->searchable()
                ->sortable(),
            NumberColumn::raw('(SELECT COUNT(*) FROM payments WHERE payment_method_id = payment_methods.id)')
                ->label('Payments')
                ->sortable()
                ->filterable(),
            ActionColumn::modal('method', 'admin.system.payment-method.form'),
        ];
    }

    public function delete($id): void
    {
        $method = PaymentMethod::find($id);
        if ($method === null) {
            $this->toast('Unable to Delete', 'Cannot find requested payment method to delete', 'danger');
            return;
        }
        if ($method->payments()->count() > 0) {
            $this->toast('Unable to Delete', 'Cannot delete method with linked payments', 'danger');
            return;
        }
        $method->delete();
    }
}
