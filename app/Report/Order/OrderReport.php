<?php

namespace App\Report\Order;

use App\Http\Livewire\Abstract\AddressColumn;
use App\Http\Livewire\Abstract\CurrencyColumn;
use App\Http\Livewire\Abstract\OrderBadgeColumn;
use App\Models\Order\Order;
use App\Models\User;
use App\Report\ColumnDefinition;
use App\Report\HasPriority;
use App\Report\Tour\TourReport;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\DatetimeColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;

class OrderReport extends TourReport
{
    use HasPriority;

    /**
     * @inheritDoc
     */
    public function getQuery()
    {
        return Order::query()
            ->leftJoin('order_caches', 'order_caches.order_id', '=', 'orders.id')
            ->leftJoin('order_customers as lead_booker', 'lead_booker.id', '=', 'orders.lead_booker_id')
            ->leftJoin('customers as lead', 'lead_booker.customer_id', '=', 'lead.id')
            ->leftJoin('addresses as lead_home', 'lead.home_address_id', '=', 'lead_home.id')
            ->leftJoin('countries as lead_home_country', 'lead_home.country_id', '=', 'lead_home_country.id')
            ->leftJoin('addresses as lead_billing', 'lead.billing_address_id', '=', 'lead_billing.id')
            ->leftJoin('countries as lead_billing_country', 'lead_billing.country_id', '=', 'lead_billing_country.id')
            ->leftJoin('tours', 'tours.id', '=', 'orders.tour_id')
            ->leftJoin('tour_categories', 'tours.tour_category_id', '=', 'tour_categories.id')
            ->leftJoin('events', 'events.id', '=', 'tours.event_id')
            ->leftJoin('users', 'users.id', '=', 'orders.consultant_id')
            ->groupBy('orders.id');
    }

    /**
     * @return ColumnDefinition[]
     */
    public function allColumns(): array
    {
        return [
            ...parent::allColumns(),
            'order_reference' =>
                new ColumnDefinition(
                    'reports.order.column.reference',
                    Column::name('orders.booking_reference')
                ),
            'order_travellers' =>
                new ColumnDefinition(
                    'reports.order.column.travellers',
                    NumberColumn::raw('(SELECT COUNT(*) FROM order_customers WHERE order_customers.order_id = orders.id AND order_customers.deleted_at IS NULL)')
                        ->filterable()
                ),
            'order_deposit' =>
                new ColumnDefinition(
                    'reports.order.column.deposit',
                    CurrencyColumn::raw('(orders.deposit * (SELECT COUNT(*) FROM order_customers WHERE order_customers.order_id = orders.id AND order_customers.deleted_at IS NULL))')
                        ->filterable()
                ),
            'order_booking_fee' =>
                new ColumnDefinition(
                    'reports.order.column.booking_fee',
                    CurrencyColumn::name('orders.booking_fee')
                        ->filterable()
                ),
            'order_status' =>
                new ColumnDefinition(
                    'reports.order.column.status',
                    OrderBadgeColumn::name('order_caches.status')
                ),
            'order_ordered' =>
                new ColumnDefinition(
                    'reports.order.column.ordered',
                    DatetimeColumn::name('orders.ordered_on')
                        ->filterable()
                ),
            'order_cancelled' =>
                new ColumnDefinition(
                    'reports.order.column.cancelled',
                    BooleanColumn::name('orders.cancelled')
                        ->filterable()
                ),
            'order_internal_notes' =>
                new ColumnDefinition(
                    'reports.order.column.internal_notes',
                    Column::name('orders.internal_notes')
                ),
            'order_external_notes' =>
                new ColumnDefinition(
                    'reports.order.column.external_notes',
                    Column::name('orders.external_notes')
                ),
            'order_invoice_footer' =>
                new ColumnDefinition(
                    'reports.order.column.invoice_footer',
                    Column::name('orders.invoice_footer')
                ),
            'order_paid' =>
                new ColumnDefinition(
                    'reports.order.column.paid',
                    CurrencyColumn::raw('(SELECT SUM(payments.amount) FROM payments WHERE payments.order_id = orders.id AND payments.deleted_at IS NULL)')
                        ->filterable()
                ),
            'order_cost' =>
                new ColumnDefinition(
                    'reports.order.column.cost',
                    CurrencyColumn::name('order_caches.cost')
                        ->filterable()
                ),
            'order_total' =>
                new ColumnDefinition(
                    'reports.order.column.total_owed',
                    CurrencyColumn::name('order_caches.total_owed')
                        ->filterable()
                ),
            'order_cost_to_company' =>
                new ColumnDefinition(
                    'reports.order.column.cost_to_company',
                    CurrencyColumn::name('order_caches.cost_to_company')
                        ->filterable()
                ),
            'order_remaining' =>
                new ColumnDefinition(
                    'reports.order.column.remaining',
                    CurrencyColumn::raw('order_caches.total_owed - (SELECT COALESCE(SUM(payments.amount), 0) FROM payments WHERE payments.order_id = orders.id AND payments.deleted_at IS NULL)')
                        ->filterable()
                ),
            'order_commission_percentage' =>
                new ColumnDefinition(
                    'reports.order.column.commission.percentage',
                    NumberColumn::callback(['orders.commission',], static function ($commission) {
                        return (empty($commission) ? 'Not Set' : $commission . "%");
                    })
                ),
            'order_commission_amount' =>
                new ColumnDefinition(
                    'reports.order.column.commission.amount',
                    CurrencyColumn::name('order_caches.commission_amount')
                        ->filterable()
                ),
            'order_next_amount' =>
                new ColumnDefinition(
                    'reports.order.column.next_payment.amount',
                    CurrencyColumn::name('order_caches.next_payment_amount')
                        ->filterable()
                ),
            'order_next_remaining' =>
                new ColumnDefinition(
                    'reports.order.column.next_payment.remaining',
                    CurrencyColumn::name('order_caches.next_payment_remaining')
                        ->filterable()
                ),
            'order_next_due' =>
                new ColumnDefinition(
                    'reports.order.column.next_payment.due',
                    DateColumn::name('order_caches.next_payment_date')
                        ->filterable()
                ),
            'order_lead_first_name' =>
                new ColumnDefinition(
                    'reports.order.column.lead_booker.first_name',
                    Column::name('lead.first_name')
                ),
            'order_lead_middle_names' =>
                new ColumnDefinition(
                    'reports.order.column.lead_booker.middle_names',
                    Column::name('lead.middle_names')
                ),
            'order_lead_last_name' =>
                new ColumnDefinition(
                    'reports.order.column.lead_booker.last_name',
                    Column::name('lead.last_name')
                ),
            'order_lead_home_address' =>
                new ColumnDefinition(
                    'reports.order.column.lead_booker.home_address',
                    AddressColumn::table('lead_home')
                ),
            'order_lead_billing_address' =>
                new ColumnDefinition(
                    'reports.order.column.lead_booker.billing_address',
                    AddressColumn::table('lead_billing')
                ),
            'order_lead_email_address' =>
                new ColumnDefinition(
                    'reports.order.column.lead_booker.email_address',
                    Column::name('lead.email_address')
                ),
            'order_lead_mobile_number' =>
                new ColumnDefinition(
                    'reports.order.column.lead_booker.mobile_number',
                    Column::name('lead.mobile_number')
                ),
            'order_consultant_name' =>
                new ColumnDefinition(
                    'reports.order.column.consultant.name',
                    Column::name('users.name')
                        ->filterOn('users.name')
                        ->filterable(User::pluck('name')),
                ),
            'order_consultant_email' =>
                new ColumnDefinition(
                    'reports.order.column.consultant.email',
                    Column::name('users.email')
                        ->filterOn('users.email')
                        ->filterable(User::pluck('email')),
                ),
        ];
    }
}
