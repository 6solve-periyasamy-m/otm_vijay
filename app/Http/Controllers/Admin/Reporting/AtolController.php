<?php

namespace App\Http\Controllers\Admin\Reporting;

use App\Exports\AtolReportExport;
use App\Helpers\QuarterCalculator;
use App\Helpers\Storage\Quarter;
use App\Http\Controllers\Controller;
use App\Repository\Model\Order\AtolRepository;
use App\Repository\Reporting\ReportRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;

class AtolController extends Controller
{
    private function getQuarter(int $year, int $quarter): Quarter|null
    {
        return (new QuarterCalculator(setting('atol.year.start', '2022-04-01')))->getQuarter($year, $quarter);
    }

    private function getPlacedOrders(int $year, int $quarter): Collection|RedirectResponse
    {
        $orders = $this->getQuarter($year, $quarter)?->getPlacedOrders();
        if ($orders === null) { return back()->withErrors(['msg' => "Cannot calculate orders for Quarter $quarter, $year"]); }
        if ($orders->count() === 0) { return back()->withErrors(['msg' => "No orders were placed in Quarter $quarter, $year"]); }
        return $orders;
    }

    private function getDepartingOrders(int $year, int $quarter): Collection|RedirectResponse
    {
        $orders = $this->getQuarter($year, $quarter)?->getPlacedOrders();
        if ($orders === null) { return back()->withErrors(['msg' => "Cannot calculate orders for Quarter $quarter $year"]); }
        if ($orders->count() === 0) { return back()->withErrors(['msg' => "No orders are departing in Quarter $quarter $year"]); }
        return $orders;
    }

    private function getDepartingAfterOrders(int $year, int $quarter): Collection|RedirectResponse
    {
        $orders = $this->getQuarter($year, $quarter)?->getPlacedOrders();
        if ($orders === null) { return back()->withErrors(['msg' => "Cannot calculate orders for Quarter $quarter $year"]); }
        if ($orders->count() === 0) { return back()->withErrors(['msg' => "No orders are departing after Quarter $quarter $year"]); }
        return $orders;
    }

    public function getOrderedInQuarterReport(int $year, int $quarter)
    {
        $orders = $this->getPlacedOrders($year, $quarter);
        if ($orders instanceof RedirectResponse) return $orders;
        return view('pages.reports.atol',
            ['data' => ReportRepository::generateAtolReport($orders),
                'csv' => route('reports.atol.export.ordered', ['year' => $year, 'quarter' => $quarter, 'extension' => 'csv',]),
                'xlsx' => route('reports.atol.export.ordered', ['year' => $year, 'quarter' => $quarter, 'extension' => 'xlsx',]),]);
    }

    public function getDepartingInQuarterReport(int $year, int $quarter)
    {
        $orders = $this->getDepartingOrders($year, $quarter);
        if ($orders instanceof RedirectResponse) return $orders;
        return view('pages.reports.atol',
            ['data' => ReportRepository::generateAtolReport($orders),
             'csv' => route('reports.atol.export.departed-in', ['year' => $year, 'quarter' => $quarter, 'extension' => 'csv',]),
             'xlsx' => route('reports.atol.export.departed-in', ['year' => $year, 'quarter' => $quarter, 'extension' => 'xlsx',]),]);
    }

    public function getDepartingAfterQuarterReport(int $year, int $quarter)
    {
        $orders = $this->getDepartingAfterOrders($year, $quarter);
        if ($orders instanceof RedirectResponse) return $orders;
        return view('pages.reports.atol',
            ['data' => ReportRepository::generateAtolReport($orders),
                'csv' => route('reports.atol.export.departs-after', ['year' => $year, 'quarter' => $quarter, 'extension' => 'csv',]),
                'xlsx' => route('reports.atol.export.departs-after', ['year' => $year, 'quarter' => $quarter, 'extension' => 'xlsx',]),]);
    }

    public function exportOrderedInQuarterReport(int $year, int $quarter, string $extension = 'csv')
    {
        $orders = $this->getPlacedOrders($year, $quarter);
        if ($orders instanceof RedirectResponse) return $orders;
        return Excel::download(new AtolReportExport($orders), "{$year}-Q{$quarter}-ordered-in.{$extension}");
    }

    public function exportDepartingInQuarterReport(int $year, int $quarter, string $extension = 'csv')
    {
        $orders = $this->getDepartingOrders($year, $quarter);
        if ($orders instanceof RedirectResponse) return $orders;
        return Excel::download(new AtolReportExport($orders), "{$year}-Q{$quarter}-departing-in.{$extension}");
    }

    public function exportDepartingAfterQuarterReport(int $year, int $quarter, string $extension = 'csv')
    {
        $orders = $this->getDepartingAfterOrders($year, $quarter);
        if ($orders instanceof RedirectResponse) return $orders;
        return Excel::download(new AtolReportExport($orders), "{$year}-Q{$quarter}-departing-after.{$extension}");
    }

    public function exportOrderedInQuarterCertificates($year, $quarter)
    {
        $orders = $this->getPlacedOrders($year, $quarter);
        if ($orders instanceof RedirectResponse) return $orders;
        $path = AtolRepository::generateAllAtolCertificates($orders, "{$year}-Q{$quarter}");
        return redirect($path);
    }

    public function exportDepartsInQuarterCertificates($year, $quarter)
    {
        $orders = $this->getDepartingOrders($year, $quarter);
        if ($orders instanceof RedirectResponse) return $orders;
        $path = AtolRepository::generateAllAtolCertificates($orders, "{$year}-Q{$quarter}");
        return redirect($path);
    }

    public function exportDepartsAfterQuarterCertificates($year, $quarter)
    {
        $orders = $this->getDepartingAfterOrders($year, $quarter);
        if ($orders instanceof RedirectResponse) return $orders;
        $path = AtolRepository::generateAllAtolCertificates($orders, "{$year}-Q{$quarter}");
        return redirect($path);
    }
}
