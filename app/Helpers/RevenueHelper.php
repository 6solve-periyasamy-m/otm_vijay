<?php

namespace App\Helpers;

use App\Models\Order\OrderInstallment;
use Carbon\Carbon;

class RevenueHelper
{
    public static function getExpectedRevenue(Carbon $start, Carbon $end): array
    {
        $installments = OrderInstallment::whereDate('due_on', '>=', $start)->whereDate('due_on', '<', $end)->with('order')->get();
        $expected = 0;
        $paid = 0;
        $count = 0;
        foreach ($installments as $installment) {
            if (!isset($installment?->order) || $installment->order->cancelled || isset($installment->order->deleted_at)) continue;
            $expected += $installment->calculated_amount;
            $paid += $installment->repository->getAmountPaid();
            $count++;
        }
        return [
            'count' => $count,
            'expected' => sigfig($expected),
            'paid' => sigfig($paid),
        ];
    }

}
