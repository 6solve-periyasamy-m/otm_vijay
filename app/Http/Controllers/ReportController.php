<?php

namespace App\Http\Controllers;

use App\Exports\OrderReportExport;
use App\Repository\ReportRepository;
use Excel;

class ReportController extends Controller
{
    public function viewReports() {
        return view('pages.reports.table', ['reports' => ReportRepository::getAvailableReports(),]);
    }

    public function getOrderReport() {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.orders',
            'data' => ReportRepository::getOrderReport(),'title' => 'Orders',
            'xlsxExport' => route('reports.order.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.order.export', ['extension' => 'csv']),]);
    }

    public function exportOrderReport(string $extension = 'xlsx') {
        return Excel::download(new OrderReportExport, 'orders.' . $extension);
    }
}
