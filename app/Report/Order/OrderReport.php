<?php

namespace App\Report\Order;

use App\Http\Livewire\Abstract\CurrencyColumn;
use App\Http\Livewire\Abstract\OrderBadgeColumn;
use App\Models\Order\Order;
use App\Report\ColumnDefinition;
use App\Report\HasPriority;
use App\Report\Tour\TourReport;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\DatetimeColumn;

class OrderReport extends TourReport
{
    use HasPriority;

    /**
     * @inheritDoc
     */
    public function getQuery()
    {
        return Order::query()
            ->join('order_caches', 'order_caches.order_id', '=', 'orders.id')
            ->join('order_customers as lead_booker', 'lead_booker.id', '=', 'orders.lead_booker_id')
            ->join('customers as lead', 'lead_booker.customer_id', '=', 'lead.id')
            ->join('addresses as lead_home', 'lead.home_address_id', '=', 'lead_home.id')
            ->join('countries as lead_home_country', 'lead_home.country_id', '=', 'lead_home_country.id')
            ->join('addresses as lead_billing', 'lead.billing_address_id', '=', 'lead_billing.id')
            ->join('countries as lead_billing_country', 'lead_billing.country_id', '=', 'lead_billing_country.id')
            ->join('tours', 'tours.id', '=', 'orders.tour_id')
            ->join('tour_categories', 'tours.tour_category_id', '=', 'tour_categories.id')
            ->join('events', 'events.id', '=', 'tour.event_id');
    }

    /**
     * @return ColumnDefinition[]
     */
    public function allColumns(): array
    {
        return [
            ...parent::allColumns(),
            'order_reference' => new ColumnDefinition('reports.order.column.reference', Column::name('orders.booking_reference')),
            'order_deposit' => new ColumnDefinition('reports.order.column.deposit', CurrencyColumn::raw('(orders.deposit * (SELECT COUNT(*) FROM order_customers WHERE order_customers.order_id = orders.id AND order_customers.deleted_at = NULL);')),
            'order_booking_fee' => new ColumnDefinition('reports.order.column.booking_fee', CurrencyColumn::name('orders.booking_fee')),
            'order_status' => new ColumnDefinition('reports.order.column.status', OrderBadgeColumn::name('order_caches.status')),
            'order_ordered' => new ColumnDefinition('reports.order.column.ordered', DatetimeColumn::name('orders.ordered_on')),
            'order_cancelled' => new ColumnDefinition('reports.order.column.cancelled', BooleanColumn::name('orders.cancelled')),
            'order_internal_notes' => new ColumnDefinition('reports.order.column.internal_notes', Column::name('orders.internal_notes')),
            'order_external_notes' => new ColumnDefinition('reports.order.column.external_notes', Column::name('orders.external_notes')),
            'order_invoice_footer' => new ColumnDefinition('reports.order.column.invoice_footer', Column::name('orders.invoice_footer')),
            'order_paid' => new ColumnDefinition('reports.order.column.paid', CurrencyColumn::raw('(SELECT SUM(payments.amount) FROM payments WHERE payments.order_id = orders.id AND payments.deleted_at = NULL)')),
            'order_cost' => new ColumnDefinition('reports.order.column.cost', CurrencyColumn::name('order_caches.cost')),
            'order_total' => new ColumnDefinition('reports.order.column.total_owed', CurrencyColumn::name('order_caches.total_owed')),
            'order_remaining' => new ColumnDefinition('reports.order.column.remaining', CurrencyColumn::raw('order_caches.cost - (SELECT SUM(payments.amount) FROM payments WHERE payments.order_id = orders.id AND payments.deleted_at = NULL)')),
            'order_next_amount' => new ColumnDefinition('reports.order.column.next_payment.amount', CurrencyColumn::name('order_caches.next_payment_amount')),
            'order_next_remaining' => new ColumnDefinition('reports.order.column.next_payment.remaining', CurrencyColumn::name('order_caches.next_payment_remaining')),
            'order_next_due' => new ColumnDefinition('reports.order.column.next_payment.remaining', DateColumn::name('order_caches.next_payment_due')),
            'order_lead_first_name' => new ColumnDefinition('reports.order.column.lead_booker.first_name', Column::name('lead.first_name')),
            'order_lead_middle_names' => new ColumnDefinition('reports.order.column.lead_booker.middle_names', Column::name('lead.middle_names')),
            'order_lead_last_name' => new ColumnDefinition('reports.order.column.lead_booker.last_name', Column::name('lead.last_name')),
            'order_lead_home_address' => new ColumnDefinition('reports.order.column.lead_booker.home_address', 
                Column::callback(['lead_home.address_line_1','lead_home.address_line_2','lead_home.town','lead_home.region','lead_home_country.name', 'lead_home.postcode',], function (...$str) {return implode(', ', $str); }),
            ),
            'order_lead_billing_address' => new ColumnDefinition('reports.order.column.lead_booker.billing_address',
                Column::callback(['lead_billing.address_line_1','lead_billing.address_line_2','lead_billing.town','lead_billing.region','lead_billing_country.name', 'lead_billing.postcode',], function (...$str) {return implode(', ', $str); }),
            ),
        ];
    }
}