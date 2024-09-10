<?php

namespace App\Http\Livewire\Admin\Report\Event;

use App\Http\Livewire\Abstract\ExportableDatatable;
use App\Models\Activity\Activity;
use App\Models\Tour\Event;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ActivityReport extends ExportableDatatable
{
    public Event|null $event = null;

    public function builder()
    {
        $query = Activity::query();
        if ($this->event !== null) {
            if ($this->event->parent !== null) {
                $this->event = $this->event->parent;
            }
            $query = $query->where('activities.event_id', '=', $this->event->id);
        }
        return $query;
    }

    public function columns()
    {
        return [
            Column::name('activities.name')
                ->label('Activity')
                ->searchable()
                ->sortable(),
            NumberColumn::raw('(SELECT COALESCE(SUM(activity_inventories.stock), 0) FROM activity_inventories WHERE activity_id = activities.id AND activity_inventories.starts_at >= DATE("' . $this->event->starts_at->subDay()->format('Y-m-d') . '") AND activity_inventories.ends_at <= DATE("' . $this->event->ends_at->addDay()->format('Y-m-d') .'"))')
                ->label('Total Stock')
                ->sortable()
                ->filterable(),
            NumberColumn::raw('(SELECT COUNT(o.id) FROM order_activities o JOIN activity_inventory_tours t ON o.activity_inventory_tour_id = t.id JOIN activity_inventories i ON t.activity_inventory_id = i.id JOIN activities a ON i.activity_id = a.id WHERE a.id = activities.id AND i.starts_at >= DATE("' . $this->event->starts_at->subDay()->format('Y-m-d') . '") AND i.ends_at <= DATE("' . $this->event->ends_at->addDay()->format('Y-m-d') .'")) AS sold')
                ->label('Sold Tickets')
                ->sortable()
                ->filterable(),
        ];
    }
}
