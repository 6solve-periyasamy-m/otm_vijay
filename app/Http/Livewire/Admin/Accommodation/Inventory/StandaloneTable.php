<?php

namespace App\Http\Livewire\Admin\Accommodation\Inventory;

use App\Http\Livewire\Abstract\StandaloneDatatable;
use App\Models\Accommodation\AccommodationInventory;
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
        $query = AccommodationInventory::query()
            ->join('accommodations', 'accommodations.id', '=', 'accommodation_inventories.accommodation_id')
            ->join('room_types', 'room_types.id', '=', 'accommodation_inventories.room_type_id')
            ->join('board_types', 'board_types.id', '=', 'accommodation_inventories.board_type_id');
        $query = $this->hideLinked($query, 'accommodation_inventories.id');
        return $this->filter($query, $this->from, $this->to, 'check_in', 'check_out');
    }

    public function columns()
    {
        return [
            Column::checkbox()
                ->width('10rem'),
            Column::name('accommodation.name')
                ->label('Accommodation')
                ->sortable()
                ->searchable(),
            Column::raw('CONCAT(room_types.name, " (", room_types.maximum_occupancy, " occupant/s)")')
                ->label('Room Type')
                ->sortable()
                ->searchable(),
            Column::name('board_types.name')
                ->label('Board Type')
                ->sortable()
                ->searchable(),
            DateColumn::name('check_in')
                ->label('Check In')
                ->sortable()
                ->filterable(),
            DateColumn::name('check_out')
                ->label('Check Out')
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
        return AccommodationInventory::class;
    }
}