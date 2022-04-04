<?php

namespace App\Helpers;

use App\Models\Order\Order;
use App\Models\Tour;
use App\Repository\SettingsRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class QuarterHelper
{
    public static function getOrdersFromToursInQuarter(int $year, int $quarter, ?Carbon $startDate = null): Collection
    {
        $orders = new Collection();
        foreach (self::getToursInQuarter($year, $quarter, $startDate) as $tour) {
            foreach ($tour->orders as $order) {
                $orders->add($order);
            }
        }
        return $orders;
    }

    public static function getToursInQuarter(int $year, int $quarter, ?Carbon $startDate = null): Collection
    {
        return Tour::whereBetween('date_from', self::getBetweenQuarters($year, $quarter, $startDate))->get();
    }

    private static function getBetweenQuarters(int $year, int $quarter, ?Carbon $startDate = null): array
    {
        return [self::getStartOfQuarter($year, $quarter, $startDate), self::getEndOfQuarter($year, $quarter, $startDate),];
    }

    public static function getStartOfQuarter(int $year, int $quarter, ?Carbon $startDate = null): Carbon
    {
        if (!isset($startDate)) $startDate = self::getYearStartDate();
        $day = $startDate->day;
        $date = $startDate->clone()->setYear($year);

        return $date->addMonthsNoOverflow(($quarter - 1) * 3)->setUnitNoOverflow('day', $day, 'month')->setTime(0, 0);
    }

    public static function getYearStartDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', SettingsRepository::getOrDefault('system.year.start', '2022-04-01'));
    }

    public static function getEndOfQuarter(int $year, int $quarter, ?Carbon $startDate = null): Carbon
    {
        return self::getStartOfQuarter($year, $quarter + 1, $startDate)->subDay()->setTime(23, 59, 59);
    }

    public static function getOrdersPlacedInQuarter(int $year, int $quarter, ?Carbon $startDate = null): Collection
    {
        return Order::whereBetween('ordered_on', self::getBetweenQuarters($year, $quarter, $startDate))->get();
    }

    public static function getOrdersFromToursAfterQuarter(int $year, int $quarter, ?Carbon $startDate = null): Collection
    {
        $orders = new Collection();
        foreach (Tour::whereDate('date_from', '>', self::getEndOfQuarter($year, $quarter, $startDate))->get() as $tour) {
            foreach ($tour->orders as $order) {
                $orders->add($order);
            }
        }
        return $orders;
    }

    public static function getAllQuarters(): array
    {
        $earliest = self::getEarliestDate();
        $latest = self::getLatestDate();
        $quarters = [];
        $ended = false;
        for ($year = $earliest->year; ; $year++) {
            for ($quarter = 1; $quarter < 5; $quarter++) {
                $quarters[] = [
                    'year' => $year,
                    'quarter' => $quarter,
                    'start' => self::getStartOfQuarter($year, $quarter),
                    'end' => self::getEndOfQuarter($year, $quarter),
                ];
                if (self::isInQuarter($latest, $year, $quarter)) {
                    $ended = true;
                    break;
                }
            }
            if ($ended) break;
        }
        return $quarters;
    }

    private static function getEarliestDate(): Carbon
    {
        $earliestOrder = Order::orderBy('ordered_on', 'asc')->first();
        $earliestTour = Tour::orderBy('date_from', 'asc')->first();
        return $earliestOrder->ordered_on->lessThan($earliestTour->date_from) ? $earliestOrder->ordered_on : $earliestTour->date_from;
    }

    private static function getLatestDate(): Carbon
    {
        $latestOrder = Order::orderBy('ordered_on', 'desc')->first();
        $latestTour = Tour::orderBy('date_from', 'desc')->first();
        return $latestOrder->ordered_on->greaterThan($latestTour->date_from) ? $latestOrder->ordered_on : $latestTour->date_from;
    }

    private static function isInQuarter($date, $year, $quarter)
    {
        return $date->between(self::getStartOfQuarter($year, $quarter), self::getEndOfQuarter($year, $quarter));
    }

    public static function getEarliestQuarter(): array
    {
        $earliest = self::getEarliestDate();
        $quarter = self::getQuarterFromDate($earliest);
        return [
            'year' => $quarter['year'],
            'quarter' => $quarter['quarter'],
            'start' => self::getStartOfQuarter($quarter['year'], $quarter['quarter']),
            'end' => self::getEndOfQuarter($quarter['year'], $quarter['quarter']),
        ];
    }

    public static function getQuarterFromDate(Carbon $date): ?array
    {
        for ($year = $date->year - 1; $year <= $date->year + 1; $year++) {
            for ($quarter = 1; $quarter < 5; $quarter++) {
                if (self::isInQuarter($date, $year, $quarter)) return ['year' => $year, 'quarter' => $quarter,];
            }
        }
        return null;
    }

    public static function getLatestQuarter(): array
    {
        $earliest = self::getLatestDate();
        $quarter = self::getQuarterFromDate($earliest);
        return [
            'year' => $quarter['year'],
            'quarter' => $quarter['quarter'],
            'start' => self::getStartOfQuarter($quarter['year'], $quarter['quarter']),
            'end' => self::getEndOfQuarter($quarter['year'], $quarter['quarter']),
        ];
    }
}
