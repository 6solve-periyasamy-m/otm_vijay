<?php

namespace App\Exports;

use App\Models\Order\Component\OrderActivity;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class OrderActivityCostReportExport implements FromView
{
    public function view(): View
    {
        return view('partials.reports.tables.component.cost.activity', ['data' => static::getOrderActivities(),]);
    }

    public static function getOrderActivities(): Collection
    {
        return OrderActivity::with([
            'orderCustomer',
            'orderCustomer.order',
            'orderCustomer.order.currency',
            'orderCustomer.order.tour',
            'orderCustomer.order.tour.event',
            'orderCustomer.customer',
            'tourComponent',
            'tourComponent.inventory',
            'tourComponent.inventory.component',
        ])->get();
    }
}
