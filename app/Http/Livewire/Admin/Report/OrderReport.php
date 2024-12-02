<?php

namespace App\Http\Livewire\Admin\Report;

use App\Http\Livewire\Abstract\CurrencyColumn;
use App\Http\Livewire\Abstract\ExportableDatatable;
use App\Http\Livewire\Abstract\OrderBadgeColumn;
use App\Models\Helper\Enum\OrderStatus;
use App\Models\Order\Order;
use App\Models\Tour\Event;
use App\Models\Tour\Tour;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\DatetimeColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;

class OrderReport extends ExportableDatatable
{
    public $name = 'order-report';

    public function builder()
    {
        return Order::query()
            ->join('order_customers as lead', 'lead.id', '=', 'orders.lead_booker_id')
            ->join('customers as lead_customer', 'lead.customer_id', '=', 'lead_customer.id')
            ->join('order_caches', 'order_caches.order_id', '=', 'orders.id')
            ->join('tours', 'orders.tour_id', '=', 'tours.id')
            ->join('events', 'tours.event_id', '=', 'events.id');
    }

    public function columns()
    {
        return [
            DatetimeColumn::name('orders.ordered_on')
                ->label('Ordered')
                ->sortable()
                ->filterable(),
            Column::name('orders.booking_reference')
                ->label('Booking Reference')
                ->sortable()
                ->searchable(),
            Column::name('lead_customer.first_name')
                ->label('Lead Booker First Name')
                ->searchable()
                ->sortable(),
            Column::name('lead_customer.last_name')
                ->label('Lead Booker last Name')
                ->searchable()
                ->sortable(),
            NumberColumn::raw('(SELECT COUNT(*) FROM order_customers WHERE order_customers.order_id = orders.id AND order_customers.deleted_at IS NULL AND order_customers.is_travelling = 1)')
                ->label('Passengers')
                ->sortable()
                ->filterable(),
            Column::name('tours.name')
                ->label('Tour')
                ->sortable()
                ->searchable()
                ->filterable(Tour::pluck('name')),
            Column::name('events.name')
                ->label('Event')
                ->sortable()
                ->searchable()
                ->filterable(Event::pluck('name')),
            CurrencyColumn::name('order_caches.total_owed')
                ->label('Total Cost')
                ->sortable()
                ->filterable(),
            CurrencyColumn::raw("(SELECT SUM(amount) FROM payments WHERE order_id = orders.id)")
                ->label('Total Paid')
                ->sortable()
                ->filterable(),
            OrderBadgeColumn::name('order_caches.status')
                ->label('Status')
                ->sortable()
                ->filterable(OrderStatus::asFilter()),
            DateColumn::name('order_caches.next_payment_date')
                ->label('Next Payment Due')
                ->sortable()
                ->filterable(),
            CurrencyColumn::name('order_caches.next_payment_amount')
                ->label('Next Payment Total')
                ->sortable()
                ->filterable(),
            CurrencyColumn::name('order_caches.next_payment_remaining')
                ->label('Next Payment Remaining')
                ->sortable()
                ->filterable(),
            Column::name('orders.internal_notes')
                ->label('Internal Notes')
                ->searchable()
                ->sortable(),
            Column::name('orders.external_notes')
                ->label('Internal Notes')
                ->searchable()
                ->sortable(),
        ];
    }
}
