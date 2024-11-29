<?php

namespace App\Http\Livewire\Admin\Activity\Inventory;

use App\Http\Livewire\Abstract\StandaloneDatatable;
use App\Models\Activity\ActivityInventory;
use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DatetimeColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;

class StandaloneTable extends StandaloneDatatable
{
    public $name = 'activity-standalone-table';

    public Carbon|string|null $from = null;
    public Carbon|string|null $to = null;
    
    public function builder()
    {
        $query = ActivityInventory::query()
            ->join('activities', 'activities.id', '=', 'activity_inventories.activity_id')
            ->join('ticket_types', 'ticket_types.id', '=', 'activity_inventories.ticket_type_id')
            ->join('activity_types', 'activity_types.id', '=', 'activities.activity_type_id');
        $query = $this->hideLinked($query, 'activity_inventories.id');
        return $this->filter($query, $this->from, $this->to, 'starts_at', 'ends_at');
    }

    public function columns()
    {
        return [
            Column::checkbox()
                ->width('10rem'),
            Column::name('activity.name')
                ->label('Activity')
                ->sortable()
                ->searchable(),
            Column::name('activity_types.name')
                ->label('Activity Type')
                ->sortable()
                ->searchable(),
            Column::name('ticket_types.name')
                ->label('Ticket Type')
                ->sortable()
                ->searchable(),
            DatetimeColumn::name('starts_at')
                ->label('Starts At')
                ->sortable()
                ->filterable(),
            DatetimeColumn::name('ends_at')
                ->label('Ends At')
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
        return ActivityInventory::class;
    }
}
