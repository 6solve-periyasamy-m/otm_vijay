<?php

namespace App\Http\Livewire\Admin\System\Conversion;

use App\Models\System\ConversionRate;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DatetimeColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    public $name = 'conversion-table';
    public bool $sales = false;

    public function builder()
    {
        return ConversionRate::query()
            ->join('currencies as from_currency', 'conversion_rates.from_currency_id', '=', 'from_currency.id')
            ->join('currencies as to_currency', 'conversion_rates.to_currency_id', '=', 'to_currency.id')
            ->where('sales', '=', $this->sales);
    }

    public function columns()
    {
        return [
            Column::callback(['from_currency.name', 'from_currency.code'], function ($name, $code) { return "$name ($code)"; })
                ->label('From')
                ->sortable()
                ->searchable()
                ->maxWidth('20rem')
                ->width('20rem'),
            Column::callback(['to_currency.name', 'to_currency.code'], function ($name, $code) { return "$name ($code)"; })
                ->label('To')
                ->sortable()
                ->searchable()
                ->maxWidth('20rem')
                ->width('20rem'),
            NumberColumn::name('rate')
                ->label('Rate')
                ->sortable(),
            DatetimeColumn::name('updated_at')
                ->label('Last Updated')
                ->sortable(),
            Column::callback(['id',], function ($id) {
                return view('partials.admin.livewire.table.actions', [
                    'id' => $id,
                    'field' => 'rate',
                    'modal' => 'admin.system.conversion.form',
                ]);
            })
                ->label(__('custom.table.actions'))
                ->unsortable(),
        ];
    }
}
