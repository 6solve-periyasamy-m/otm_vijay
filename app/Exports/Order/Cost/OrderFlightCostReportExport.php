<?php

namespace App\Exports\Order\Cost;

use App\Models\Order\Component\OrderFlight;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class OrderFlightCostReportExport implements FromView
{
    public function view(): View
    {
        return view('partials.reports.tables.component.cost.flight', ['data' => static::getComponents(),]);
    }

    public static function getComponents(): Collection
    {
        return OrderFlight::with([
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
            'tourComponent.inventory.component',
            'tourComponent.inventory.travelClass',
            'tourComponent.inventory.component.currency',
        ])->get();
    }
}
