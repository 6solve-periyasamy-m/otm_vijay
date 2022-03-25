<?php

namespace App\Helpers;

use App\Models\Order;
use App\Models\Tour;
use App\Repository\SettingsRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class QuarterHelper
{
    public static function getYearStartDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', SettingsRepository::getOrDefault('system.year.start', '2022-04-01'));
    }

    public static function getStartOfQuarter(int $year, int $quarter, ?Carbon $startDate = null): Carbon
    {
        if (!isset($startDate)) $startDate = self::getYearStartDate();
        $day = $startDate->day;
        $date = $startDate->clone()->setYear($year);

        return $date->addMonthsNoOverflow(($quarter - 1) * 3)->setUnitNoOverflow('day', $day, 'month')->setTime(0,0);
    }

    public static function getEndOfQuarter(int $year, int $quarter, ?Carbon $startDate = null): Carbon
    {
        return self::getStartOfQuarter($year, $quarter + 1, $startDate)->subDay()->setTime(23,59,59);
    }

    public static function getToursInQuarter(int $year, int $quarter, ?Carbon $startDate = null): Collection
    {
        return Tour::whereBetween('date_from', self::getBetweenQuarters($year, $quarter, $startDate))->get();
    }

    public static function getOrdersFromToursInQuarter(int $year, int $quarter, ?Carbon $startDate = null): Collection
    {
        $orders = new Collection();
        foreach (self::getToursInQuarter($year, $quarter, $startDate) as $tour) {
            $orders->add($tour->orders);
        }
        return $orders;
    }

    public static function getOrdersPlacedInQuarter(int $year, int $quarter, ?Carbon $startDate = null): Collection
    {
        return Order::whereBetween('ordered_on', self::getBetweenQuarters($year, $quarter, $startDate))->get();
    }

    private static function getBetweenQuarters(int $year, int $quarter, ?Carbon $startDate = null): array
    {
        return [self::getStartOfQuarter($year, $quarter, $startDate), self::getEndOfQuarter($year, $quarter, $startDate),];
    }
}