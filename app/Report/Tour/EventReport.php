<?php

namespace App\Report\Tour;

use App\Models\Tour\Event;
use App\Report\BespokeReport;
use App\Report\ColumnDefinition;
use App\Report\HasPriority;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;

class EventReport extends BespokeReport
{
    use HasPriority;

    public function getQuery()
    {
        return Event::query();
    }

    public function allColumns(): array
    {
        return [
            'event_name' =>
                new ColumnDefinition(
                    'reports.event.column.name',
                    Column::name('events.name')->filterable(Event::orderBy('name')->pluck('name')),
                ),
            'event_description' =>
                new ColumnDefinition(
                    'reports.event.column.description',
                    Column::name('events.description')
                ),
            'event_start' =>
                new ColumnDefinition(
                    'reports.event.column.start',
                    DateColumn::name('events.starts_at')->filterable()
                ),
            'event_end' =>
                new ColumnDefinition(
                    'reports.event.column.end',
                    DateColumn::name('events.ends_at')->filterable()
                ),
            'event_notes' =>
                new ColumnDefinition(
                    'reports.event.column.notes',
                    Column::name('events.notes')
                ),
            'event_tours' =>
                new ColumnDefinition(
                    'reports.event.column.tours',
                    NumberColumn::raw('(SELECT COUNT(*) FROM tours WHERE tours.event_id = events.id;)')
                ),
        ];
    }
}
