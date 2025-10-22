<?php

namespace App\Http\Livewire\Admin\Quote;

use App\Http\Livewire\Abstract\ActionColumn;
use App\Http\Livewire\Abstract\QuoteBadgeColumn;
use App\Models\Customer\Customer;
use App\Models\Helper\Enum\QuoteStatus;
use App\Models\Quote\Quote;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public $name = 'all-quotes-table';
    public $defaultFilters = ['quotes.archived' => '0',];

    public function builder()
    {
        return Quote::query()
            ->leftJoin('quote_prospects', 'quote_prospects.id', '=', 'quotes.lead_traveller_id')
            ->leftJoin('customers', 'customers.id', '=', 'quote_prospects.customer_id')
            ->leftJoin('events', 'events.id', '=', 'quotes.event_id');
    }

    public function getColumns(): array
    {
        return $this->columns();
    }

    public function columns(): array
    {
        return [
            Column::name('quotes.reference')
                ->label('Reference')
                ->searchable()
                ->sortable(),
            Column::name('quotes.name')
                ->label('Package')
                ->searchable()
                ->sortable()
                ->editable()
                ->filterable(Quote::pluck('name')->unique()),
            Column::name('events.name')
                ->label('Event Name')
                ->searchable()
                ->sortable(),
            DateColumn::name('quotes.created_at')
                ->label('Quote Created')
                ->sortable()
                ->searchable()
                ->filterable()
                ->format('d/m/Y'),
            Column::callback(['customers.title', 'customers.first_name', 'customers.last_name'], static function (...$fields) { return implode(' ', $fields); })
                ->label('Lead Traveller')
                ->searchable()
                ->sortable(),
            Column::name('customers.email_address')
                ->label('Email')
                ->searchable()
                ->sortable()
                ->filterable(Customer::pluck('email_address')),
            DateColumn::name('quotes.expires')
                ->label('Expiry')
                ->searchable()
                ->sortable()
                ->filterable(),
            QuoteBadgeColumn::raw('(IF(quotes.order_id IS NULL, IF(NOW() < quotes.expires, quotes.quote_status, IF(quotes.quote_status < 2, -1, quotes.quote_status)), 4))')
                ->label('Status')
                ->filterable(QuoteStatus::asFilter()),
            BooleanColumn::name('quotes.archived')
                ->label('Archived')
                ->sortable()
                ->filterable(),
            ActionColumn::view('quote', 'quotes.edit', 'quotes.view', 'partials.admin.livewire.table.archive-actions'),
        ];
    }

    public function archive($id)
    {
        $quote = Quote::find($id);
        if ($quote !== null) {
            $quote->archived = !$quote->archived;
            $quote->save();
        }
    }
}
