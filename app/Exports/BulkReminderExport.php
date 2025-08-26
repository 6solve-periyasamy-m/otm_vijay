<?php

namespace App\Exports;

use App\Models\Order\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BulkReminderExport implements FromView
{
    /** @var Order[] */
    private array $orders;

    public function __construct(array $orders)
    {
        $this->orders = $orders;
    }

    public function view(): View
    {
        return view('partials.reports.tables.bulk-reminders', ['orders' => $this->orders,]);
    }
}