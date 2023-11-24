<?php

namespace App\Http\Livewire\Admin\Supplier\Contract\Component;

use App\Models\Flight\FlightInventory;
use App\Models\Supplier\SupplierContract;
use App\Models\Supplier\SupplierContractComponent;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class FlightTable extends LivewireDatatable
{
    public SupplierContract $contract;

    public function builder()
    {
        return SupplierContractComponent::query()
            ->where('component_type', '=', FlightInventory::class)
            ->where('supplier_contract_id', '=', $this->contract->id)
            ->join('flight_inventories', 'supplier_contract_components.component_id', '=', 'flight_inventories.id')
            ->join('flights', 'flights.id', '=', 'flight_inventories.flight_id')
            ->join('airlines', 'airlines.id', '=', 'flights.airline_id')
            ->join('airports as departure', 'departure.id', '=', 'flights.departure_airport_id')
            ->join('airports as arrival', 'arrival.id', '=', 'flights.departure_airport_id')
            ->join('travel_classes', 'travel_classes.id', '=', 'flight_inventories.travel_class_id');
    }

    public function columns()
    {
        return [
            Column::callback(['departure.name', 'arrival.name'], function ($departure, $arrival) {
                return "$departure to $arrival";
            })
                ->label('Flight Details')
                ->sortable()
                ->searchable(),
            Column::name('flight_inventories.flight_number')
                ->label('Flight Number')
                ->sortable()
                ->searchable(),
            Column::name('airlines.name')
                ->label('Airline')
                ->sortable()
                ->searchable(),
            Column::name('travel_classes.name')
                ->label('Travel Class')
                ->sortable()
                ->searchable(),
            DateColumn::name('flight_inventories.departs_at')
                ->label('Departure')
                ->sortable()
                ->filterable(),
            DateColumn::name('flight_inventories.arrives_at')
                ->label('Arrival')
                ->sortable()
                ->filterable(),
            NumberColumn::callback(['cost_per_unit'], function ($price) {
                return f_currency($price);
            })
                ->label('Cost Per Unit')
                ->sortable()
                ->filterable(),
            NumberColumn::name('quantity')
                ->label('Quantity')
                ->sortable()
                ->filterable(),
        ];
    }
}