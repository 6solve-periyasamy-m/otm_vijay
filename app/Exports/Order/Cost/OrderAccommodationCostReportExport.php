<?php

namespace App\Exports\Order\Cost;

use App\Models\Order\Component\OrderAccommodation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class OrderAccommodationCostReportExport implements FromView
{
    public function view(): View
    {
        return view('partials.reports.tables.component.cost.accommodation', ['data' => static::getComponents(),]);
    }

    public static function getComponents(): Collection
    {
        return OrderAccommodation::with([
            'orderCustomers',
            'group',
            'group.orderCustomers',
            'group.orderCustomers.order',
            'group.orderCustomers.order.tour',
            'group.orderCustomers.order.leadBooker',
            'group.orderCustomers.order.leadBooker.customer',
            'group.orderCustomers.order.tour.event',
            'group.orderCustomers.customer',
            'tourComponent',
            'tourComponent.inventory',
            'tourComponent.inventory.component',
            'tourComponent.inventory.component.currency',
        ])->withCount('orderCustomers')->get();
    }
}
