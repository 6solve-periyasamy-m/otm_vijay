<?php

namespace App\Exports\Order\Cost;

use App\Models\Order\Component\OrderTransport;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class OrderTransportCostReportExport implements FromView
{
    public function view(): View
    {
        return view('partials.reports.tables.component.cost.transport', ['data' => static::getComponents(),]);
    }

    public static function getComponents(): Collection
    {
        return OrderTransport::with([
            'orderCustomer',
            'orderCustomer.order',
            'orderCustomer.order.leadBooker',
            'orderCustomer.order.leadBooker.customer',
            'orderCustomer.order.currency',
            'orderCustomer.order.tour',
            'orderCustomer.order.tour.event',
            'orderCustomer.customer',
            'tourComponent',
            'tourComponent.inventory',
            'tourComponent.inventory.component.currency',
        ])->get();
    }
}
