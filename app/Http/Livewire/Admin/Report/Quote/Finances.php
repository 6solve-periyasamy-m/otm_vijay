<?php

namespace App\Http\Livewire\Admin\Report\Quote;

use App\Http\Livewire\Abstract\CurrencyColumn;
use App\Http\Livewire\Abstract\ExportableDatatable;
use App\Models\Customer\Organization;
use App\Models\Quote\Quote;
use App\Models\Tour\Event;
use App\Models\User;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Finances extends ExportableDatatable
{
    public function builder()
    {
        return Quote::query()
            ->leftJoin('users', 'users.id', '=', 'quotes.consultant_id')
            ->leftJoin('quote_prospects', 'quote_prospects.id', '=', 'quotes.lead_traveller_id')
            ->leftJoin('quote_caches', 'quote_caches.quote_id', '=', 'quotes.id')
            ->leftJoin('customers', 'customers.id', '=', 'quote_prospects.customer_id')
            ->leftJoin('events', 'events.id', '=', 'quotes.event_id')
            ->leftJoin('organizations', 'organizations.id', '=', 'quotes.organization_id')
            ->leftJoin('orders', 'orders.id', '=', 'quotes.order_id');
    }

    public function initialiseDefaultFilters(): void
    {
        parent::initialiseDefaultFilters();

        $columns = collect($this->columns);

        $columnIndex = $columns->search(function ($column) {
            return $column['name'] === 'quotes.date_from';
        });

        if ($columnIndex !== false) {
            $this->doDateFilterStart($columnIndex, now()->format('Y-m-d'));
        }
    }

    public function columns(): array
    {
        return [
            Column::name('events.name')
                ->label('Event')
                ->searchable()
                ->sortable()
                ->filterable(Event::pluck('name')),
            Column::name('quotes.reference')
                ->label('Reference')
                ->sortable()
                ->searchable(),
            Column::raw('CONCAT(COALESCE(customers.first_name, ""), " ", COALESCE(customers.last_name, "")) AS name')
                ->label('Lead Traveller Name')
                ->searchable()
                ->sortable(),
            Column::name('organizations.name')
                ->label('Organization')
                ->searchable()
                ->sortable()
                ->filterable(Organization::pluck('name')),
            DateColumn::name('quotes.date_from')
                ->label('Event Start')
                ->sortable()
                ->filterable(),
            DateColumn::name('quotes.date_to')
                ->label('Event End')
                ->sortable()
                ->filterable(),
            // Final Booking Value
            CurrencyColumn::name('quote_caches.total')
                ->label('Total')
                ->sortable()
                ->filterable(),
            // Cost to company
            CurrencyColumn::name('quote_caches.cost_to_company')
                ->label('Cost to Company')
                ->sortable()
                ->filterable(),
            // Tax Amount
            CurrencyColumn::name('quote_caches.tax_amount')
                ->label('Taxes')
                ->sortable()
                ->filterable(),
            // Profit
            CurrencyColumn::name('quote_caches.profit')
                ->label('Profit')
                ->sortable()
                ->filterable(),
            // Margin
            NumberColumn::name('quote_caches.margin')
                ->label('Margin (%)')
                ->sortable()
                ->filterable(),
            Column::name('users.name')
                ->label('Consultant')
                ->searchable()
                ->sortable()
                ->filterable(User::pluck('name')),
            Column::raw('IF(ISNULL(quotes.order_id), "QUOTE", "ORDER") AS status')
                ->label('Status')
                ->sortable()
                ->filterable(['QUOTE', 'ORDER']),
            Column::name('orders.booking_reference')
                ->label('Order Reference')
                ->searchable()
                ->sortable(),
        ];
    }
}