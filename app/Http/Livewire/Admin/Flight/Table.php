<?php

namespace App\Http\Livewire\Admin\Flight;

use App\Actions\Flight\DeleteFlight;
use App\Exceptions\CannotDeleteException;
use App\Http\Livewire\Abstract\ActionColumn;
use App\Models\Flight\Airline;
use App\Models\Flight\Flight;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use App\Http\Livewire\SendsEvents;

class Table extends LivewireDatatable
{
    use SendsEvents;

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

    public function delete($id): void
    {
        $flight = Flight::find($id);
        if ($flight === null) {
            $this->toast('Cannot Delete Flight', 'The requested flight was not found.', 'danger');
        }
        if (! auth()->user()->can('self-child-access', [$flight, \App\Models\Flight\FlightInventory::class])) {
            $this->toast('Unauthorized', 'You do not have permission to delete this Flight.', 'danger');
            return;
        }
        try {
            DeleteFlight::run(Flight::find($id));
            $this->toast('flight Deleted Successfully', 'Successfully deleted the requested flight.', 'success');
        } catch (CannotDeleteException $e) {
            $this->toast('Cannot Delete flight', $e->getMessage(), 'danger');
        }
    }
}