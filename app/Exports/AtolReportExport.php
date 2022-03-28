<?php

namespace App\Exports;

use App\Models\Report;
use App\Repository\BespokeReportRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class AtolReportExport implements FromView
{
    private $orders;

    public function __construct(array $orders) {
        $this->orders = $orders;
    }

    public function view(): View
    {
        return view('partials.reports.atol', ['orders' => $this->orders, 'format' => false,]);
    }
}
