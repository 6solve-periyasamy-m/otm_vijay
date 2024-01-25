<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class AtolReportExport implements FromView
{
    private $orders;

    public function __construct(Collection|array $orders) {
        $this->orders = $orders;
    }

    public function view(): View
    {
        return view('partials.reports.atol', ['orders' => $this->orders, 'format' => false,]);
    }
}
