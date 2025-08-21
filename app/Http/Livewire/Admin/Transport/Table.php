<?php

namespace App\Http\Livewire\Admin\Transport;

use App\Http\Livewire\Abstract\ActionColumn;
use App\Http\Livewire\Abstract\AddressColumn;
use App\Models\Transport\Operator;
use App\Models\Transport\Transport;
use App\Models\Transport\TransportType;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public bool $archived = false;
    public function builder()
    {
        $query = Transport::query()
            ->leftJoin('operators', 'operators.id', '=', 'transports.operator_id')
            ->leftJoin('transport_types', 'transport_types.id', '=', 'transports.transport_type_id')
            ->leftJoin('addresses as departure', 'departure.id', '=', 'transports.departure_address_id')
            ->leftJoin('countries as departure_country', 'departure.country_id', '=', 'departure_country.id')
            ->leftJoin('addresses as arrival', 'arrival.id', '=', 'transports.arrival_address_id')
            ->leftJoin('countries as arrival_country', 'arrival.country_id', '=', 'arrival_country.id');
            //->leftJoin('currencies', 'currencies.id', '=', 'transports.currency_id');
        if (!$this->archived) {
            $query = $query->where('archived', '=', false);
        }
        return $query;
    }

    public function columns()
    {
        $archiveColumn = BooleanColumn::name('archived')->label('Archived')->filterable();
        $this->archived || $archiveColumn->hide();

        return [
            Column::callback(['id', 'name'], static function ($id, $name) { return '<a href="' . route('transports.view', ['transport' => $id]) . '">' . $name . '</a>'; })
                ->label('Name')
                ->sortable()
                ->searchable(),
            Column::name('transport_types.name')
                ->label('Transport')
                ->sortable()
                ->searchable()
                ->filterable(TransportType::pluck('name')),
            Column::name('operators.name')
                ->label('Operator')
                ->sortable()
                ->searchable()
                ->filterable(Operator::pluck('name')),
            AddressColumn::table('departure', 'departure_country')
                ->label('Departure')
                ->sortable()
                ->searchable(),
            AddressColumn::table('arrival', 'arrival_country')
                ->label('Arrival')
                ->sortable()
                ->searchable(),
            // Column::name('currencies.name')
            //     ->label('Currency')
            //     ->sortable()
            //     ->searchable(),
            Column::name('internal_notes')
                ->label('Notes')
                ->sortable()
                ->searchable(),
            $archiveColumn,
            ActionColumn::view('transport', 'transports.edit', 'transports.view'),
        ];
    }
}