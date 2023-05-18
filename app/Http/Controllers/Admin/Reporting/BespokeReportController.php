<?php

namespace App\Http\Controllers\Admin\Reporting;

use App\Exports\BespokeReportExport;
use App\Http\Controllers\Controller;
use App\Models\System\Report;
use App\Repository\Reporting\BespokeReportRepository;
use App\Repository\Reporting\ReportFieldRepository;
use App\Repository\Reporting\ReportRepository;
use Excel;
use Illuminate\Http\Request;

class BespokeReportController extends Controller
{
    public function index() {
        return view('pages.reports.table', ['reports' => Report::all(), 'system' => ReportRepository::getAvailableReports(),]);
    }

    public function create(string $parent) {
        return view('pages.reports.create', ['parent' => $parent, 'fieldList' => ReportFieldRepository::getFieldsFromParent($parent)]);
    }

    public function showTemporary(Request $request) {
        $request->validate(BespokeReportRepository::getValidationRules());
        $parent = $request->input('parent');
        $fields = ReportFieldRepository::convertFieldsToOutput(ReportFieldRepository::getFieldsFromParent($parent));
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
        return view('pages.reports.output', BespokeReportRepository::showReport($report, true));
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

    public function apiExport(Request $request) {
        $report = Report::make([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'parent' => $request->input('parent'),
            'fields' => $request->input('fields'),
        ]);
        return Excel::download(new BespokeReportExport($report), $report->parent . '-report-' . now() . '.' . $request->input('filetype'));
    }

    public function show(Report $report) {
        return view('pages.reports.show', BespokeReportRepository::showReport($report, true));
    }

    public function export(Report $report, string $extension) {
        return Excel::download(new BespokeReportExport($report), $report->parent . '-report-' . now() . '.' . $extension);
    }

    public function edit(Report $report) {
        return view('pages.reports.edit', ['report' => $report, 'fieldList' => ReportFieldRepository::getFieldsFromParent($report->parent),]);
    }

    public function update(Request $request, Report $report) {
        $request->validate(BespokeReportRepository::getValidationRules());
        $fields = ReportFieldRepository::convertFieldsToOutput(ReportFieldRepository::getFieldsFromParent($report->parent));
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
        $report->delete();
        return redirect()->route('reports.all');
    }
}
