<?php

namespace App\Report\Tour;

use App\Http\Livewire\Abstract\CurrencyColumn;
use App\Models\Tour\Tour;
use App\Models\Tour\TourCategory;
use App\Report\ColumnDefinition;
use App\Report\HasPriority;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;

class TourReport extends EventReport
{
    use HasPriority;

    public function getQuery()
    {
        return Tour::query()
            ->leftJoin('events', 'events.id', '=', 'tours.event_id')
            ->leftJoin('tour_categories', 'tour_categories.id', '=', 'tours.tour_category_id');
    }

    public function allColumns(): array
    {
        $base = "reports.tour.column";
        return [
            ...parent::allColumns(),
            'tour_name' => new ColumnDefinition("$base.name", Column::name('tours.name')->filterable(Tour::pluck('name'))),
            'tour_description' => new ColumnDefinition("$base.description", Column::name('tours.description')),
            'tour_base_price' => new ColumnDefinition("$base.base_price", CurrencyColumn::name('tours.base_price_per_person')),
            'tour_margin' => new ColumnDefinition("$base.margin", NumberColumn::name('tours.margin')),
            'tour_single_occupancy' => new ColumnDefinition("$base.single_occupancy", CurrencyColumn::name('tours.single_occupancy_surcharge')),
            'tour_deposit' => new ColumnDefinition("$base.deposit", CurrencyColumn::name('tours.deposit')),
            'tour_booking_fee' => new ColumnDefinition("$base.booking_fee", CurrencyColumn::name('tours.booking_fee')),
            'tour_total_stock' => new ColumnDefinition("$base.total_stock", NumberColumn::name('tours.stock')),
            'tour_used_stock' => new ColumnDefinition("$base.used_stock", NumberColumn::raw('(SELECT COUNT(*) FROM orders WHERE tour_id = tours.id AND cancelled = FALSE;)')),
            'tour_category' => new ColumnDefinition("$base.category", Column::name('tour_categories.category')->filterable(TourCategory::pluck('name'))),
            'tour_active' => new ColumnDefinition("$base.active", BooleanColumn::name('tours.is_active')),
            'tour_from' => new ColumnDefinition("$base.from", DateColumn::name('tours.date_from')->filterable()),
            'tour_to' => new ColumnDefinition("$base.to", DateColumn::name('tours.date_to')->filterable()),
            'tour_final_payment' => new ColumnDefinition("$base.final_payment", DateColumn::name('tours.final_payment')->filterable()),
        ];
    }
}