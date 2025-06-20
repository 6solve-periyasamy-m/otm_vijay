<?php

namespace App\Http\Controllers\Admin\Order\Component;

use App\Exports\OrderActivityCostReportExport;
use App\Http\Controllers\Controller;
use Excel;

class OrderComponentCostReportController extends Controller
{

    public function getOrderActivityReport() {
        return view('pages.reports.view', ['tableView' => 'partials.reports.tables.component.cost.activity',
            'data' => OrderActivityCostReportExport::getOrderActivities(),'title' => 'Order Activity Costs Report',
            'xlsxExport' => route('reports.component.cost.activity.export', ['extension' => 'xlsx']),
            'csvExport' => route('reports.component.cost.activity.export', ['extension' => 'csv']),]);
    }

    public function exportOrderActivityReport(string $extension = 'xlsx') {
        return Excel::download(new OrderActivityCostReportExport, 'order-activity-costs.' . $extension);
    }
}