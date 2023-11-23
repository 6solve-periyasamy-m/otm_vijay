<?php

namespace App\Http\Livewire\Admin\Transport\Inventory;

use App\Http\Livewire\Abstract\StandaloneDatatable;
use App\Models\Transport\TransportInventory;
use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;

class StandaloneTable extends StandaloneDatatable
{
    public Carbon|string|null $from = null;
    public Carbon|string|null $to = null;

    public function builder()
    {
        $query = TransportInventory::query()
            ->join('transports', 'transports.id', '=', 'transport_inventories.transport_id')
            ->join('transport_types', 'transports.transport_type_id', '=', 'transport_types.id')
            ->join('operators', 'transports.operator_id', '=', 'operators.id')
            ->join('travel_classes', 'travel_classes.id', '=', 'transport_inventories.travel_class_id');
        return $this->filter($query, $this->from, $this->to, 'departs_at', 'arrives_at');
    }

    public function columns()
    {
        return [
            Column::checkbox()
                ->width('10rem'),
            Column::name()
                ->label('Name')
                ->sortable()
                ->searchable(),
            Column::name('transport_number')
                ->label('Transport Number')
                ->sortable()
                ->searchable(),
            Column::name('operators.name')
                ->label('Operator')
                ->sortable()
                ->searchable(),
            Column::name('travel_classes.name')
                ->label('Travel Class')
                ->sortable()
                ->searchable(),
            DateColumn::name('departs_at')
                ->label('Departure')
                ->sortable()
                ->filterable(),
            DateColumn::name('arrives_at')
                ->label('Arrival')
                ->sortable()
                ->filterable(),
            NumberColumn::callback(['purchase_price'], function ($price) { return f_currency($price); })
                ->label('Purchase Price')
                ->sortable()
                ->filterable(),
            NumberColumn::callback(['sales_price'], function ($price) { return f_currency($price); })
                ->label('Sales Price')
                ->sortable()
                ->filterable(),
        ];
    }

    public function getClass(): string
    {
        return TransportInventory::class;
    }
}