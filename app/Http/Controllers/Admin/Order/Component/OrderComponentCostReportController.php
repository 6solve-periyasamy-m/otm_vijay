<?php

namespace App\Http\Controllers\Admin\Order\Component;

use App\Exports\Order\Cost\OrderAccommodationCostReportExport;
use App\Exports\Order\Cost\OrderActivityCostReportExport;
use App\Exports\Order\Cost\OrderFlightCostReportExport;
use App\Exports\Order\Cost\OrderMerchandiseCostReportExport;
use App\Exports\Order\Cost\OrderTransportCostReportExport;
use App\Http\Controllers\Controller;
use Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderComponentCostReportController extends Controller
{
    public function getOrderAccommodationReport()
    {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.component.cost.accommodation',
            'data' => OrderAccommodationCostReportExport::getComponents(), 'title' => 'Order Accommodation Costs Report',
            'xlsxExport' => route('reports.component.cost.accommodation.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.component.cost.accommodation.export', ['extension' => 'csv']),]);
    }

    public function exportOrderAccommodationReport(string $extension = 'xlsx'): BinaryFileResponse
    {
        return Excel::download(new OrderAccommodationCostReportExport, 'order-accommodation-costs.' . $extension);
    }

    public function getOrderActivityReport()
    {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.component.cost.activity',
            'data' => OrderActivityCostReportExport::getComponents(), 'title' => 'Order Activity Costs Report',
            'xlsxExport' => route('reports.component.cost.activity.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.component.cost.activity.export', ['extension' => 'csv']),]);
    }

    public function exportOrderActivityReport(string $extension = 'xlsx'): BinaryFileResponse
    {
        return Excel::download(new OrderActivityCostReportExport, 'order-activity-costs.' . $extension);
    }

    public function getOrderFlightReport()
    {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.component.cost.flight',
            'data' => OrderFlightCostReportExport::getComponents(), 'title' => 'Order Flight Costs Report',
            'xlsxExport' => route('reports.component.cost.flight.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.component.cost.flight.export', ['extension' => 'csv']),]);
    }

    public function exportOrderFlightReport(string $extension = 'xlsx'): BinaryFileResponse
    {
        return Excel::download(new OrderFlightCostReportExport, 'order-flight-costs.' . $extension);
    }

    public function getOrderTransportReport()
    {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.component.cost.transport',
            'data' => OrderTransportCostReportExport::getComponents(), 'title' => 'Order Transport Costs Report',
            'xlsxExport' => route('reports.component.cost.transport.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.component.cost.transport.export', ['extension' => 'csv']),]);
    }

    public function exportOrderTransportReport(string $extension = 'xlsx'): BinaryFileResponse
    {
        return Excel::download(new OrderTransportCostReportExport, 'order-transport-costs.' . $extension);
    }

    public function getOrderMerchandiseReport()
    {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.component.cost.merchandise',
            'data' => OrderMerchandiseCostReportExport::getComponents(), 'title' => 'Order Merchandise Costs Report',
            'xlsxExport' => route('reports.component.cost.merchandise.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.component.cost.merchandise.export', ['extension' => 'csv']),]);
    }

    public function exportOrderMerchandiseReport(string $extension = 'xlsx'): BinaryFileResponse
    {
        return Excel::download(new OrderMerchandiseCostReportExport, 'order-merchandise-costs.' . $extension);
    }
}