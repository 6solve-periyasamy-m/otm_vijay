<?php

namespace App\Http\Livewire\Admin\Order;

use App\Models\Helper\Enum\OrderStatus;
use App\Models\Order\Order;
use App\Models\Tour\Tour;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DatetimeColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    public $name = "order-table";

    public function builder()
    {
        return Order::query()
            ->join('order_customers as lead', 'lead.id', '=', 'orders.lead_booker_id')
            ->join('customers as lead_customer', 'lead.customer_id', '=', 'lead_customer.id')
            ->join('order_caches', 'order_caches.order_id', '=', 'orders.id')
            ->join('tours', 'tours.id', '=', 'orders.tour_id')
            ->groupBy('orders.id');
    }

    public function columns()
    {
        return [
            Column::raw('(SELECT GROUP_CONCAT(CONCAT(customers.first_name, " ", customers.last_name) SEPARATOR ", ") FROM order_customers JOIN customers ON customers.id = order_customers.customer_id WHERE order_customers.order_id = orders.id)')
                ->label('Travellers')
                ->searchable()
                ->hide(),
            DatetimeColumn::name('orders.ordered_on')
                ->label('Ordered On')
                ->sortable()
                ->searchable()
                ->filterable(),
            Column::callback(['orders.id', 'orders.booking_reference'], function ($id, $reference) {
                return '<a href="' . route('orders.view', ['order' => $id]) . '">' . $reference . "</a>";
            })
                ->label('Booking Reference')
                ->sortable()
                ->searchable(),
            Column::name('tours.name')
                ->label("Tour")
                ->sortable()
                ->searchable()
                ->filterable(Tour::pluck('name')),
            Column::raw('CONCAT(COALESCE(lead_customer.first_name, ""), " ", COALESCE(lead_customer.last_name, ""))')
                ->label("Lead Traveller Name")
                ->sortable()
                ->searchable(),
            NumberColumn::raw("(SELECT COUNT(*) FROM order_customers WHERE order_customers.order_id = orders.id)")
                ->label("Passengers")
                ->sortable()
                ->searchable(),
            Column::callback('order_caches.status', function ($status) {
                return(new \App\View\Components\Badge\Order(OrderStatus::from($status)))->render();
            })
                ->label("Order Status")
                ->sortable()
                ->filterable(OrderStatus::asFilter())

        ];
    }
}
