<?php

namespace App\Helpers;

use App\Models\Order\OrderInstallment;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class RevenueHelper
{
    public static function getExpectedRevenue(Carbon $start, Carbon $end): array
    {
        $installments = OrderInstallment::whereDate('due_on', '>=', $start)->whereDate('due_on', '<=', $end)->with('order')->get();
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

    public static function getRevenue(Carbon $start = null, Carbon $end = null): Collection|array
    {
        $query = DB::table('payments');
        if (isset($start)) {
            $query->whereRaw("DATE(`paid_on`) > '{$start->format('Y-m-d')}'");
        }
        if (isset($end)) {
            $query->whereRaw("DATE(`paid_on`) < '{$end->format('Y-m-d')}'");
        }
        $query->groupBy(DB::raw('DATE(`paid_on`)'));
        $query->select(DB::raw('DATE(`paid_on`) as date'), DB::raw('SUM(`amount`) as amount'));
        return $query->get();
    }

}
