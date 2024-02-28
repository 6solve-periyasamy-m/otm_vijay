<?php

namespace App\Report\Tour;

use App\Models\Tour\Event;
use App\Report\BespokeReport;
use App\Report\ColumnDefinition;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;

class EventReport extends BespokeReport
{

    public function getQuery()
    {
        return Event::query();
    }

    public function allColumns(): array
    {
        return [
            'events.name' => new ColumnDefinition('reports.event.column.name', Column::name('events.name')),
            'events.description' => new ColumnDefinition('reports.event.column.description', Column::name('events.description')),
            'events.start' => new ColumnDefinition('reports.event.column.start', DateColumn::name('events.starts_at')->filterable()),
            'events.end' => new ColumnDefinition('reports.event.column.end', DateColumn::name('events.ends_at')->filterable()),
            'events.notes' => new ColumnDefinition('reports.event.column.notes', Column::name('events.notes')),
            'events.tours' => new ColumnDefinition('reports.event.column.tours', NumberColumn::raw('(SELECT COUNT(*) FROM tours WHERE tours.event_id = events.id;)')),
        ];
    }
}