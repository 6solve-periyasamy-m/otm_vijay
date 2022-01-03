<?php

namespace App\Http\Controllers;

use App\Exports\OrderReportExport;
use App\Exports\PaymentReportExport;
use App\Exports\TourStockReportExport;
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

    public function getTourStockReport() {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.tour-stock',
            'data' => ReportRepository::getTourStockReport(),'title' => 'Tour Stock',
            'xlsxExport' => route('reports.tour-stock.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.tour-stock.export', ['extension' => 'csv']),]);
    }

    public function exportTourStockReport(string $extension = 'xlsx') {
        return Excel::download(new TourStockReportExport, 'tour-stock.' . $extension);
    }

    public function getPaymentsReport() {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.payment',
            'data' => ReportRepository::getPaymentReport(),'title' => 'Tour Stock',
            'xlsxExport' => route('reports.payment.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.payment.export', ['extension' => 'csv']),]);
    }

    public function exportPaymentsReport(string $extension = 'xlsx') {
        return Excel::download(new PaymentReportExport, 'payments.' . $extension);
    }
}
