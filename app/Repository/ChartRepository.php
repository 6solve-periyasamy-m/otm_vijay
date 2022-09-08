<?php

namespace App\Repository;

use App\Helpers\RevenueHelper;
use App\Models\Tour\Tour;
use App\View\Components\Chart\Donut;
use App\View\Components\Chart\Line;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;

class ChartRepository
{
    public static function getRevenueChart(Carbon $from, Carbon $to = null): Closure|View|string
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
        return (new Line($name, $labels, $values))->render();
    }

    public static function getTourRevenueDonut(Tour $tour): Closure|View|string
    {
        $potential = $tour->repository->getPotentialRevenue();
        $received = $tour->repository->getReceivedRevenue();
        $remaining = $tour->repository->getRemainingRevenue();
        $labels = ['Received', 'Remaining',];
        $values = [$received, $remaining,];
        $colors = ['#090', '#900',];
        if ($potential > -1) {
            $labels[] = 'Potential';
            $values[] = $potential-$remaining-$received;
            $colors[] = '#AAA';
        }
        return (new Donut($tour->name . ' Revenue', $labels, $values, $colors))->render();
    }
}
