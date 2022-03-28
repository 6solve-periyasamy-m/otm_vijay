<?php

namespace App\Http\Controllers;

use App\Helpers\QuarterHelper;
use App\Repository\ReportRepository;
use JetBrains\PhpStorm\ArrayShape;

class AtolController extends Controller
{
    #[ArrayShape(['earliest' => "int", 'latest' => "int"])]
    private function getBoundaries(): array
    {
        $earliest = QuarterHelper::getEarliestQuarter();
        $latest = QuarterHelper::getLatestQuarter();
        return ['earliest' => $earliest, 'latest' => $latest];
    }

    private function verifyBoundaries(int $year, int $quarter): bool
    {
        $boundaries = $this->getBoundaries();
        if ($quarter < 1 || $quarter > 4) return false;
        if (($year < $boundaries['earliest']['year'] && $quarter < $boundaries['earliest']['quarter'])
            || ($year > $boundaries['latest']['year'] && $quarter > $boundaries['latest']['quarter'])) return false;
        return true;
    }

    public function getOrderedInQuarterReport(int $year, int $quarter)
    {
        if (!$this->verifyBoundaries($year, $quarter)) abort(404);
        return view('pages.reports.atol',
            ['data' => ReportRepository::getOrdersPlacedInQuarterReport($year, $quarter),]);
    }

    public function getDepartingInQuarterReport(int $year, int $quarter)
    {
        if (!$this->verifyBoundaries($year, $quarter)) abort(404);
        return view('pages.reports.atol',
            ['data' => ReportRepository::getOrdersDepartingInQuarterReport($year, $quarter),]);
    }

    public function getDepartingAfterQuarterReport(int $year, int $quarter)
    {
        if (!$this->verifyBoundaries($year, $quarter)) abort(404);
        return view('pages.reports.atol',
            ['data' => ReportRepository::getOrdersDepartingAfterQuarterReport($year, $quarter),]);
    }
}
