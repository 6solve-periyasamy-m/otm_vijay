<?php

namespace App\Http\Livewire\Admin\Flight;

use App\Http\Livewire\Abstract\ActionColumn;
use App\Models\Flight\Airline;
use App\Models\Flight\Flight;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public $name = 'flight-table';
    public bool $archived = false;
    public function builder()
    {
        $query = Flight::query()
            ->leftJoin('airlines', 'airlines.id', '=', 'flights.airline_id')
            ->leftJoin('airports as departure', 'departure.id', '=', 'flights.departure_airport_id')
            ->leftJoin('airports as arrival', 'arrival.id', '=', 'flights.arrival_airport_id');
        if (!$this->archived) {
            $query = $query->where('archived', '=', false);
        }
        return $query;
    }

    public function columns(): array
    {
        $archiveColumn = BooleanColumn::name('archived')->label('Archived')->filterable();
        $this->archived || $archiveColumn->hide();

        return [
            Column::callback(['id', 'airlines.name'], function ($id, $airline) {
                return '<a href="' . route('flights.view', ['flight' => $id]) . '">' . $airline . '</a>';
            })
                ->label('Airline')
                ->searchable()
                ->sortable()
                ->filterable(Airline::pluck('name')),
            Column::name('departure.name')
                ->label('Departure Airport')
                ->searchable()
                ->sortable(),
            Column::name('arrival.name')
                ->label('Arrival Airport')
                ->searchable()
                ->sortable(),
            BooleanColumn::name('is_domestic')
                ->label('Is Domestic')
                ->filterable(),
            DateColumn::name('available_from')
                ->label('Available From')
                ->filterable(),
            Column::name('internal_notes')
                ->label('Internal Notes')
                ->searchable(),
            $archiveColumn,
            ActionColumn::view('flight', 'flights.edit', 'flights.view'),
        ];
    }
}