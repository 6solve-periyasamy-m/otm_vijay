<?php

namespace App\Http\Livewire\Admin\Flight\Inventory;

use App\Http\Livewire\Abstract\StandaloneDatatable;
use App\Models\Flight\FlightInventory;
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
        $query = FlightInventory::query()
            ->join('flights', 'flights.id', '=', 'flight_inventories.flight_id')
            ->join('airlines', 'airlines.id', '=', 'flights.airline_id')
            ->join('airports as departure', 'departure.id', '=', 'flights.departure_airport_id')
            ->join('airports as arrival', 'arrival.id', '=', 'flights.departure_airport_id')
            ->join('travel_classes', 'travel_classes.id', '=', 'flight_inventories.travel_class_id');
        $query = $this->hideLinked($query, 'flight_inventories.id');
        return $this->filter($query, $this->from, $this->to, 'departs_at', 'arrives_at');
    }

    public function columns()
    {
        return [
            Column::checkbox()
                ->width('10rem'),
            Column::callback(['departure.name', 'arrival.name'], function ($departure, $arrival) {
                return "$departure to $arrival";
            })
                ->label('Flight Details')
                ->sortable()
                ->searchable(),
            Column::name('flight_number')
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
        return FlightInventory::class;
    }
}