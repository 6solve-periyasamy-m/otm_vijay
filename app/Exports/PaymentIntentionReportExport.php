<?php

namespace App\Exports;

use App\Models\Order\Payment\PaymentIntention;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentIntentionReportExport implements FromView
{
    public function view(): View
    {
        return view('partials.reports.tables.payment-intentions', ['data' => PaymentIntention::all(),]);
    }
}
