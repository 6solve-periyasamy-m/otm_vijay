<?php

namespace App\Helpers;

use App\Models\Order\Order;
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

    public static function getAllExpectedRevenue(): array
    {
        $installments = OrderInstallment::with('order', 'order.payments')->get();
        $installments->push(...self::getAllRemainingInstallments());
        $data = [];
        foreach ($installments as $installment) {
            if ($installment->order?->cancelled) continue;
            if (array_key_exists($installment->due_on->unix(), $data)) {
                $data[$installment->due_on->unix()] = [
                    'count' => $data[$installment->due_on->unix()]['count'] + 1,
                    'expected' => sigfig($data[$installment->due_on->unix()]['expected'] + $installment->calculated_amount),
                    'paid' => sigfig($data[$installment->due_on->unix()]['paid'] + $installment->repository->getAmountPaid())
                ];
            } else {
                $data[$installment->due_on->unix()] = [
                    'count' => 1,
                    'expected' => sigfig($installment->calculated_amount),
                    'paid' => sigfig($installment->repository->getAmountPaid())
                ];
            }
        }
        return $data;
    }

    private static function getAllRemainingInstallments(): array
    {
        $installments = [];
        foreach (Order::where('cancelled', '=', false)->get() as $order) {
            $installment = $order->repository->generateRemainingOrderInstallment();
            $installments[] = $installment;
        }
        return $installments;
    }

}
