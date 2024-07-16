<?php

namespace App\Http\Livewire\Admin\System\PaymentMethod;

use App\Models\Order\Payment\PaymentMethod;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    public function builder()
    {
        return PaymentMethod::query();
    }

    public function columns()
    {
        return [
            Column::name('name')
                ->label('Name')
                ->searchable()
                ->sortable()
                ->editable(),
            NumberColumn::raw('(SELECT COUNT(*) FROM payments WHERE payment_method_id = payment_methods.id)')
                ->label('Payments')
                ->sortable()
                ->filterable()
        ];
    }
}
