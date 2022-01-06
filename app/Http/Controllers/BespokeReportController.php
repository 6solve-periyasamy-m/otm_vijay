<?php

namespace App\Http\Controllers;

use App\Exports\BespokeReportExport;
use App\Exports\OrderReportExport;
use App\Exports\PaymentReportExport;
use App\Exports\TourStockReportExport;
use App\Models\Report;
use App\Repository\BespokeReportRepository;
use App\Repository\ReportRepository;
use Excel;
use Illuminate\Http\Request;

class BespokeReportController extends Controller
{
    public function index() {

    }

    public function create(string $parent) {
        return view('pages.reports.create', ['parent' => $parent, 'fieldList' => BespokeReportRepository::getFieldsFromParent($parent)]);
    }

    public function showTemporary(Request $request) {
        $parent = $request->input('parent');
        $fields = BespokeReportRepository::convertFieldsToOutput(BespokeReportRepository::getFieldsFromParent($parent));
        $usedFields = [];
        foreach ($fields as $field => $data) {
            if ($request->has($field)) {
                $usedFields[] = $field;
            }
        }
        $report = Report::make([
            'name' => $request->input('report_name'),
            'description' => $request->input('report_description'),
            'parent' => $parent,
            'fields' => $usedFields,
        ]);
        return view('pages.reports.output', array_merge(['report' => $report,], BespokeReportRepository::showReport($report)));
    }

    public function store(Request $request) {
        $report = Report::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'parent' => $request->input('parent'),
            'fields' => $request->input('fields'),
        ]);
        return response(['success' => true, 'message' => route('reports.bespoke.show', ['report' => $report,])]);
    }

    public function show(Report $report) {
        return view('pages.reports.show', BespokeReportRepository::showReport($report));
    }

    public function export(Report $report, string $extension) {
        return Excel::download(new BespokeReportExport($report), $report->parent . '-report-' . now() . '.' . $extension);
    }

    public function edit(Report $report) {
        return view('pages.reports.edit', ['report' => $report, 'fieldList' => BespokeReportRepository::getFieldsFromParent($report->parent),]);
    }

    public function update(Request $request, Report $report) {
        $fields = BespokeReportRepository::convertFieldsToOutput(BespokeReportRepository::getFieldsFromParent($report->parent));
        $usedFields = [];
        foreach ($fields as $field => $data) {
            if ($request->has($field)) {
                $usedFields[] = $field;
            }
        }
        $report->update([
            'name' => $request->input('report_name'),
            'description' => $request->input('report_description'),
            'fields' => $usedFields,
        ]);
        $report->save();
        return redirect()->route('reports.bespoke.show', ['report' => $report,]);
    }

    public function delete(Report $report) {
        // TODO: Stub (Implement)
    }
}
