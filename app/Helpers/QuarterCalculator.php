<?php

namespace App\Helpers;

use App\Helpers\Storage\Quarter;
use Carbon\Carbon;

class QuarterCalculator
{
    private Carbon $yearStart;

    public function __construct(string $yearStart)
    {
        $this->yearStart = Carbon::createFromFormat('Y-m-d', $yearStart);
    }

    public function getQuarter($year, $quarter): Quarter|null
    {
        if ($quarter > 4 || $quarter < 1) return null;
        return new Quarter($this->getStartOfQuarter($year, $quarter), $this->getEndOfQuarter($year, $quarter), $year, $quarter);
    }

    private function getStartOfQuarter(int $year, int $quarter): Carbon
    {
        $day = $this->yearStart->day;
        $date = $this->yearStart->clone()->setYear($year);

        return $date->addMonthsNoOverflow(($quarter - 1) * 3)->setUnitNoOverflow('day', $day, 'month')->setTime(0, 0);
    }

    private function getEndOfQuarter(int $year, int $quarter): Carbon
    {
        return $this->getStartOfQuarter($year, $quarter + 1)->subDay()->setTime(23, 59, 59);
    }
}
