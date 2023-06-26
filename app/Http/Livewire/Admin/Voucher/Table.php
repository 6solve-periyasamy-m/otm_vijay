<?php

namespace App\Http\Livewire\Admin\Voucher;

use App\Models\Voucher\VoucherCode;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    public function builder()
    {
        return VoucherCode::query();
    }

    public function columns()
    {
        return [
            Column::name('code')
                ->sortable()
                ->searchable(),
            Column::name('name')
                ->sortable()
                ->searchable()
                ->editable(),
            Column::name('description')
                ->sortable()
                ->searchable()
                ->editable(),
            BooleanColumn::raw('IF(NOW() < expiry AND active = 1, 1, 0)')
                ->label('Active')
                ->filterable()
                ->sortable(),
            DateColumn::name('expiry')
                ->sortable(),
            NumberColumn::name('results.id:count')
                ->label('Results Count')
                ->sortable(),
        ];
    }
}
