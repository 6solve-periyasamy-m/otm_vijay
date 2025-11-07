<?php

namespace App\Http\Livewire\Admin\Order;

use App\Models\Helper\Enum\OrderStatus;
use App\Models\Order\Order;
use App\Models\Tour\Tour;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DatetimeColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;
use App\Models\Customer\Organization;
use App\Http\Livewire\Abstract\ActionColumn;
use App\Models\User;

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
            ->leftJoin('events', 'events.id', '=', 'tours.event_id')
            ->leftJoin('organizations', 'organizations.id', '=', 'orders.organization_id')
            ->leftJoin('users', 'users.id', '=', 'orders.consultant_id')
            ->groupBy('orders.id');
    }

    public function columns()
    {
        return [
            Column::raw('(SELECT GROUP_CONCAT(CONCAT(customers.first_name, " ", customers.last_name) SEPARATOR ", ") FROM order_customers JOIN customers ON customers.id = order_customers.customer_id WHERE order_customers.order_id = orders.id)')
                ->label('Travellers')
                ->searchable()
                ->hide(),
            Column::raw('DATE_FORMAT(orders.ordered_on, "%d/%m/%Y")')
                ->label('Order Date')
                ->sortable()
                ->searchable()
                ->filterable(),
            Column::callback(['orders.id', 'orders.booking_reference'], function ($id, $reference) {
                return '<a href="' . route('orders.view', ['order' => $id]) . '">' . $reference . "</a>";
            })
                ->label('Booking Reference')
                ->sortable()
                ->searchable(),
            Column::name('events.name')
                ->label('Event')
                ->sortable()
                ->searchable()
                ->filterable(\App\Models\Tour\Event::orderBy('name')->pluck('name')->toArray()), 
            Column::raw('CONCAT(COALESCE(lead_customer.first_name, ""), " ", COALESCE(lead_customer.last_name, ""))')
                ->label("Lead Traveller Name")
                ->sortable()
                ->searchable(),
            NumberColumn::raw("(SELECT COUNT(*) FROM order_customers WHERE order_customers.order_id = orders.id)")
                ->label("No of Passengers")
                ->sortable()
                ->searchable(),
            Column::name('organizations.name')
                ->label('Organization')
                ->sortable()
                ->searchable()
                ->filterable(Organization::pluck('name')->toArray()),
            Column::callback('order_caches.status', function ($status) {
                return(new \App\View\Components\Badge\Order(OrderStatus::from($status)))->render();
                })
                ->label("Order Status")
                ->sortable()
                ->filterable(OrderStatus::asFilter()),
            Column::name('users.name')
                ->label('Consultant Name')
                ->sortable()
                ->searchable()
                ->filterable(User::pluck('name')->toArray()),
            Column::callback(['id'], function ($id) {
                    $viewUrl = route('orders.view', ['order' => $id]);
                    $editUrl = route('orders.edit', ['order' => $id]);
                    return sprintf(
                        '<div class="d-flex gap-1"><a href="%s" class="btn btn-outline-info btn-sm mb-1" title="View Order"><i class="fas fa-eye"></i></a>
                        <a href="%s" class="btn btn-outline-success btn-sm mb-1" title="Edit Order"><i class="fas fa-edit"></i></a></div>',
                        e($viewUrl),
                        e($editUrl)
                    );
                })
                ->label('Actions')
                ->unsortable()
                ->excludeFromExport(),
        ];
    }
}
