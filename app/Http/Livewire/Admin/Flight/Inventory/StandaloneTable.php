<?php

namespace App\Http\Livewire\Admin\Flight\Inventory;

use App\Models\Flight\FlightInventory;
use Carbon\Carbon;
use DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class StandaloneTable extends LivewireDatatable
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
            ->join('travel_classes', 'travel_classes.id', '=', 'flight_inventories.travel_class_id')
        ->select(['*', DB::raw("CONCAT(`departure.name`, ' to ', `arrival.name`) as name")]);
        if (!empty($this->from)) {
            $query->where('departs_at', '>', is_string($this->from) ? Carbon::parse($this->from) : $this->from);
        }
        if (!empty($this->to)) {
            $query->where('arrives_at', '<', is_string($this->to) ? Carbon::parse($this->to) : $this->to);
        }
        return $query;
    }

    public function columns()
    {
        return [
            Column::checkbox()
                ->width('10rem'),
            Column::name('name')
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
}