<?php

namespace App\Repository;

use App\Helpers\RevenueHelper;
use App\View\Components\Chart\Line;
use Carbon\Carbon;

class ChartRepository
{
    public static function getRevenueChart(Carbon $from, Carbon $to = null)
    {
        if (isset($to)) {
            $name = "Revenue from " . f_date($from) . " to ". f_date($to);
        } else {
            $name = "Revenue for the last " . now()->diffInDays($from) . " days";
        }
        $labels = [];
        $values = [];
        foreach (RevenueHelper::getRevenue($from, $to) as $data) {
            $labels[] = $data->date;
            $values[] = $data->amount;
        }
        return new Line($name, $labels, $values);
    }
}
