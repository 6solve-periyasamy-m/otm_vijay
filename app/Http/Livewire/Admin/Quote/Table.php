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
use App\Models\Customer\Organization;
use App\Models\User;

class Table extends LivewireDatatable
{
    public $name = 'all-quotes-table';
    public $defaultFilters = ['quotes.archived' => '0',];

    public function builder()
    {
        return Quote::query()
            ->leftJoin('events', 'events.id', '=', 'quotes.event_id')
            ->leftJoin('organizations', 'organizations.id', '=', 'quotes.organization_id')
            ->leftJoin('users', 'users.id', '=', 'quotes.consultant_id')
            ->groupBy('quotes.id');
    }

    public function getColumns(): array
    {
        return $this->columns();
    }

    public function columns(): array
    {
        return [
            DateColumn::name('quotes.created_at')
                ->label('Quote Created')
                ->sortable()
                ->searchable()
                ->filterable()
                ->format('d/m/Y'),
            Column::callback(['quotes.id', 'quotes.reference'], function ($id, $reference) {
                    $url = route('quotes.view', ['quote' => $id]);
                    return "<a href='{$url}' class='text-primary  hover:underline'>{$reference}</a>";
                })
                ->label('Booking Reference')
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
                ->sortable()
                ->filterable(\App\Models\Tour\Event::orderBy('name')->pluck('name')->toArray()),
            Column::callback(['quotes.id'], fn($id) =>
                    f_currency(Quote::find($id)?->final_price ?? 0.00, Quote::find($id)?->currency)
                )
                ->label('Final Price'),
            Column::name('organizations.name')
                ->label('Organization')
                ->sortable()
                ->searchable()
                ->filterable(Organization::pluck('name')->toArray()),
            QuoteBadgeColumn::raw('(IF(quotes.order_id IS NULL, IF(NOW() < quotes.expires, quotes.quote_status, IF(quotes.quote_status < 2, -1, quotes.quote_status)), 4))')
                ->label('Status')
                ->filterable(QuoteStatus::asFilter()),
            Column::name('users.name')
                ->label('Consultant Name')
                ->sortable()
                ->searchable()
                ->filterable(User::pluck('name')->toArray()),                                
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
