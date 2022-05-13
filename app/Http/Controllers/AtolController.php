<?php

namespace App\Http\Controllers;

use App\Exports\AtolReportExport;
use App\Helpers\QuarterHelper;
use App\Repository\Model\Order\AtolRepository;
use App\Repository\StaticOrderRepository;
use App\Repository\ReportRepository;
use Maatwebsite\Excel\Facades\Excel;

class AtolController extends Controller
{
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
            ['data' => ReportRepository::getOrdersPlacedInQuarterReport($year, $quarter),
                'csv' => route('reports.atol.export.ordered', ['year' => $year, 'quarter' => $quarter, 'extension' => 'csv',]),
                'xlsx' => route('reports.atol.export.ordered', ['year' => $year, 'quarter' => $quarter, 'extension' => 'xlsx',]),]);
    }

    public function getDepartingInQuarterReport(int $year, int $quarter)
    {
        if (!$this->verifyBoundaries($year, $quarter)) abort(404);
        return view('pages.reports.atol',
            ['data' => ReportRepository::getOrdersDepartingInQuarterReport($year, $quarter),
             'csv' => route('reports.atol.export.departed-in', ['year' => $year, 'quarter' => $quarter, 'extension' => 'csv',]),
             'xlsx' => route('reports.atol.export.departed-in', ['year' => $year, 'quarter' => $quarter, 'extension' => 'xlsx',]),]);
    }

    public function getDepartingAfterQuarterReport(int $year, int $quarter)
    {
        if (!$this->verifyBoundaries($year, $quarter)) abort(404);
        return view('pages.reports.atol',
            ['data' => ReportRepository::getOrdersDepartingAfterQuarterReport($year, $quarter),
                'csv' => route('reports.atol.export.departs-after', ['year' => $year, 'quarter' => $quarter, 'extension' => 'csv',]),
                'xlsx' => route('reports.atol.export.departs-after', ['year' => $year, 'quarter' => $quarter, 'extension' => 'xlsx',]),]);
    }

    public function exportOrderedInQuarterReport(int $year, int $quarter, string $extension = 'csv')
    {
        if (!$this->verifyBoundaries($year, $quarter)) abort(404);
        return Excel::download(new AtolReportExport(ReportRepository::getOrdersPlacedInQuarterReport($year, $quarter)->orders), "{$year}-Q{$quarter}-ordered-in.{$extension}");
    }

    public function exportDepartingInQuarterReport(int $year, int $quarter, string $extension = 'csv')
    {
        if (!$this->verifyBoundaries($year, $quarter)) abort(404);
        return Excel::download(new AtolReportExport(ReportRepository::getOrdersDepartingInQuarterReport($year, $quarter)->orders), "{$year}-Q{$quarter}-departing-in.{$extension}");
    }

    public function exportDepartingAfterQuarterReport(int $year, int $quarter, string $extension = 'csv')
    {
        if (!$this->verifyBoundaries($year, $quarter)) abort(404);
        return Excel::download(new AtolReportExport(ReportRepository::getOrdersDepartingAfterQuarterReport($year, $quarter)->orders), "{$year}-Q{$quarter}-departing-after.{$extension}");
    }

    public function exportOrderedInQuarterCertificates($year, $quarter)
    {
        if (!$this->verifyBoundaries($year, $quarter)) abort(404);
        $orders = QuarterHelper::getOrdersPlacedInQuarter($year, $quarter);
        $path = AtolRepository::generateAllAtolCertificates($orders, "{$year}-Q{$quarter}");
        return redirect($path);
    }

    public function exportDepartsInQuarterCertificates($year, $quarter)
    {
        if (!$this->verifyBoundaries($year, $quarter)) abort(404);
        $orders = QuarterHelper::getOrdersFromToursInQuarter($year, $quarter);
        $path = AtolRepository::generateAllAtolCertificates($orders, "{$year}-Q{$quarter}");
        return redirect($path);
    }

    public function exportDepartsAfterQuarterCertificates($year, $quarter)
    {
        if (!$this->verifyBoundaries($year, $quarter)) abort(404);
        $orders = QuarterHelper::getOrdersFromToursAfterQuarter($year, $quarter);
        $path = AtolRepository::generateAllAtolCertificates($orders, "{$year}-Q{$quarter}");
        return redirect($path);
    }
}
