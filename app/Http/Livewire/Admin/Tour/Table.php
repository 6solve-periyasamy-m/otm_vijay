<?php

namespace App\Http\Livewire\Admin\Tour;

use App\Exceptions\CannotDeleteException;
use App\Http\Livewire\Abstract\ActionColumn;
use App\Http\Livewire\Abstract\CurrencyColumn;
use App\Http\Livewire\Abstract\DisplayModeColumn;
use App\Http\Livewire\SendsEvents;
use App\Models\Helper\Enum\EventType;
use App\Models\Tour\Event;
use App\Models\Tour\Tour;
use App\Models\Tour\TourCategory;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public $name = 'tour-table';

    use SendsEvents;

    public bool $hideNoCategory = false;
    public function builder()
    {
        $query = Tour::query()
                ->leftJoin('tour_categories', 'tour_categories.id', '=', 'tours.tour_category_id')
                ->leftJoin('events', 'events.id', '=', 'tours.event_id');
        if ($this->hideNoCategory) {
            $query->whereNotNull('tours.tour_category_id');
        }
        return $query;
    }

    public function getColumns(): array
    {
        // Updating to Laravel 11 will break the old method due to inheritance, hence future-proofing
        // TODO: When upgraded to laravel 11, remove columns method
        return $this->columns();
    }

    public function columns()
    {
        return [
            Column::name('tours.name')
                ->label('Name')
                ->sortable()
                ->searchable(),
            Column::name('events.name')
                ->label('Event')
                ->sortable()
                ->searchable()
                ->filterable(Event::where('event_category', '=', EventType::NORMAL)->orderBy('name')->pluck('name')),
            DisplayModeColumn::table('tour_categories')
                ->label('Category')
                ->searchable()
                ->sortable()
                ->filterable(TourCategory::pluck('name')),
            DateColumn::name('tours.date_from')
                ->label('From')
                ->sortable()
                ->filterable(),
            DateColumn::name('tours.date_to')
                ->label('To')
                ->sortable()
                ->filterable(),
            CurrencyColumn::name('tours.base_price_per_person')
                ->label('Base Price')
                ->sortable()
                ->filterable(),
            CurrencyColumn::name('tours.deposit')
                ->label('Deposit')
                ->sortable()
                ->filterable(),
            BooleanColumn::name('tours.is_active')
                ->label('Active')
                ->sortable()
                ->filterable(),
            ActionColumn::view('tour', 'tours.edit', 'tours.view', 'partials.admin.livewire.table.tour-actions'),
        ];
    }

    public function delete($id)
    {
        $tour = Tour::find($id);
        try {
            $tour->repository->delete();
        } catch (CannotDeleteException $e) {
            $this->toast('Cannot Delete Tour', $e->getMessage(), 'danger');
        }

    }
}
