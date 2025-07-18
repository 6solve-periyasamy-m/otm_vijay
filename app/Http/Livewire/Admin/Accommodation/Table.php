<?php

namespace App\Http\Livewire\Admin\Accommodation;

use App\Actions\Accommodation\DeleteAccommodation;
use App\Exceptions\CannotDeleteException;
use App\Http\Livewire\Abstract\ActionColumn;
use App\Http\Livewire\Abstract\AddressColumn;
use App\Http\Livewire\SendsEvents;
use App\Models\Accommodation\Accommodation;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    use SendsEvents;

    public $name = "accommodation-table";
    public bool $archived = false;

    public function builder()
    {
        $query = Accommodation::query()
                ->leftJoin('addresses', 'accommodations.address_id', '=', 'addresses.id')
                ->leftJoin('countries', 'addresses.country_id', '=', 'countries.id');
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
            Column::name('name')
                ->label('Name')
                ->searchable()
                ->sortable(),
            Column::name('description')
                ->label('Description')
                ->searchable()
                ->sortable(),
            DateColumn::name('audit_date')
                ->label('Audit Date')
                ->searchable()
                ->sortable()
                ->filterable(),
            AddressColumn::country('countries')
                ->label('Countries')
                ->searchable()
                ->sortable(),
            AddressColumn::table('addresses', 'countries')
                ->label('Address')
                ->searchable()
                ->sortable(),
            $archiveColumn,
            ActionColumn::view('accommodation', 'accommodations.edit', 'accommodations.view'),
        ];
    }

    public function delete($id): void
    {
        $activity = Accommodation::find($id);
        if ($activity === null) {
            $this->toast('Cannot Delete Activity', 'The requested activity was not found.', 'danger');
        }
        try {
            DeleteAccommodation::run(Accommodation::find($id));
            $this->toast('Activity Deleted Successfully', 'Successfully deleted the requested activity.', 'success');
        } catch (CannotDeleteException $e) {
            $this->toast('Cannot Delete Activity', $e->getMessage(), 'danger');
        }
    }
}
