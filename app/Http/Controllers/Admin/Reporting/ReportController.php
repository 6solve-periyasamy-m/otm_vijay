<?php

namespace App\Http\Controllers\Admin\Reporting;

use App\Exports\AbandonedBookingsReportExport;
use App\Exports\BookingOrdersReportExport;
use App\Exports\ActivitiesReportExport;
use App\Exports\FinalPaymentReportExport;
use App\Exports\FlightManifestReportExport;
use App\Exports\InstallmentRevenueReportExport;
use App\Exports\OrderMerchandiseExport;
use App\Exports\OrderReminderReportExport;
use App\Exports\OrderReportExport;
use App\Exports\PaymentIntentionReportExport;
use App\Exports\PaymentReportExport;
use App\Exports\TourStockReportExport;
use App\Http\Controllers\Controller;
use App\Models\Order\Payment\PaymentIntention;
use App\Repository\Reporting\Manifest\RoomingReportRepository;
use App\Repository\Reporting\ReportRepository;
use Excel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function viewReports() {
        return view('pages.reports.table', ['reports' => ReportRepository::getAvailableReports(),]);
    }

    public function getOrderReport() {
        return view('pages.admin.report.order');
    }

    public function exportOrderReport(string $extension = 'xlsx') {
        return Excel::download(new OrderReportExport, 'orders.' . $extension);
    }

    public function getFinalPaymentReport() {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.final-payments',
            'data' => ReportRepository::getFinalPaymentReport(),'title' => 'Final Payments',
            'xlsxExport' => route('reports.final-payment.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.final-payment.export', ['extension' => 'csv']),]);
    }

    public function exportFinalPaymentReport(string $extension = 'xlsx') {
        return Excel::download(new FinalPaymentReportExport, 'final-payments.' . $extension);
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

    public function getFlightManifestReport() {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.flight-manifest',
            'data' => ReportRepository::getFlightManifestReport(),'title' => 'Flight Details',
            'xlsxExport' => route('reports.flight-manifest.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.flight-manifest.export', ['extension' => 'csv']),]);
    }

    public function exportFlightManifestReport(string $extension = 'xlsx')
    {
        return Excel::download(new FlightManifestReportExport, 'flight-manifest.' . $extension);
    }

    public function getActivitiesReport() {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.activities',
            'data' => ReportRepository::getActivityReport(),'title' => 'Activity Customer',
            'xlsxExport' => route('reports.activities.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.activities.export', ['extension' => 'csv']),]);
    }

    public function exportActivitiesReport(string $extension = 'xlsx')
    {
        return Excel::download(new ActivitiesReportExport(), 'activities.' . $extension);
    }

    public function getAbandonedBookingsReport() {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.abandoned-bookings',
            'data' => ReportRepository::getAbandonedBookingsReport(),'title' => 'Abandoned Bookings',
            'xlsxExport' => route('reports.abandoned-bookings.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.abandoned-bookings.export', ['extension' => 'csv']),]);
    }

    public function exportAbandonedBookingsReport(string $extension = 'xlsx')
    {
        return Excel::download(new AbandonedBookingsReportExport(), 'abandoned-bookings.' . $extension);
    }

    public function exportdBookingOrdersReport(string $extension = 'xlsx')
    {
        return Excel::download(new BookingOrdersReportExport(), 'booking-orders.' . $extension);
    }

    /**
     * Abandoned bookings report where the lead booker has at least one piece of contact information
     */
    public function getAbandonedBookingsHiddenReport()
    {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.abandoned-bookings',
            'data' => ReportRepository::getAbandonedBookingsReport(null,  true),'title' => 'Abandoned Bookings',
            'xlsxExport' => route('reports.abandoned-bookings-hidden.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.abandoned-bookings-hidden.export', ['extension' => 'csv']),]);
    }

    /**
     * Export for abandoned bookings report where the lead booker has at least one piece of contact information
     */
    public function exportAbandonedBookingsHiddenReport(string $extension = 'xlsx')
    {
        return Excel::download(new AbandonedBookingsReportExport(true), 'abandoned-bookings.' . $extension);
    }

    public function getOrderRemindersReport(int $max = 7, int $min = -1000) {
        return view('pages.reports.reminders', ['tableView' => 'partials.reports.tables.reminders',
            'data' => ReportRepository::getRemindersReport($max, $min),'title' => 'Order Reminders',
            'xlsxExport' => route('reports.reminders.export', ['extension' => 'xlsx', 'max' => $max, 'min' => $min,]),
            'csvExport' => route('reports.reminders.export', ['extension' => 'csv', 'max' => $max, 'min' => $min,]),
            'min' => $min, 'max' => $max,]);
    }

    public function exportOrderRemindersReport(string $extension = 'xlsx', int $max = 7, int $min = -1000)
    {
        return Excel::download(new OrderReminderReportExport($max, $min), 'reminders.' . $extension);
    }

    public function getOrderMerchandiseReport() {
        return view('pages.reports.merchandise', ['tableView' => 'partials.reports.tables.merchandise',
            'data' => ReportRepository::getOrderMerchandiseReport(),'title' => 'Merchandise Orders',
            'xlsxExport' => route('reports.merchandise.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.merchandise.export', ['extension' => 'csv']),]);
    }

    public function exportOrderMerchandiseReport(string $extension = 'xlsx')
    {
        return Excel::download(new OrderMerchandiseExport(), 'order-merchandise.' . $extension);
    }

    public function getRoomingReport(Request $request)
    {
        $notes = !$request->has('notes') || $request->notes == true;
        return RoomingReportRepository::viewReport(new RoomingReportRepository(), 'reports.rooming.export', $notes);
    }

    public function exportRoomingReport(Request $request, string $extension = 'xlsx')
    {
        $notes = !$request->has('notes') || $request->notes == true;
        return RoomingReportRepository::exportReport(new RoomingReportRepository(), $extension, $notes);
    }

    public function getInstallmentRevenueReport() {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.installment-revenue',
            'data' => ReportRepository::getInstallmentRevenueReport(),'title' => 'Installment Revenue',
            'xlsxExport' => route('reports.installment-revenue.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.installment-revenue.export', ['extension' => 'csv']),]);
    }

    public function exportInstallmentRevenueReport(string $extension = 'xlsx')
    {
        return Excel::download(new InstallmentRevenueReportExport(), 'installment-revenue.' . $extension);
    }

    public function getPaymentIntentionReport() {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.payment-intentions',
            'data' => PaymentIntention::all(),'title' => 'Intention Report',
            'xlsxExport' => route('reports.payment-intentions.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.payment-intentions.export', ['extension' => 'csv']),]);
    }

    public function exportPaymentIntentionReport(string $extension = 'xlsx')
    {
        return Excel::download(new PaymentIntentionReportExport(), 'payment-intentions.' . $extension);
    }

}
