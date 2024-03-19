<?php

namespace App\Http\Controllers\Admin\Reporting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Report\AdvancedReportRequest;
use App\Models\System\BespokeReport;
use App\Report\Order\OrderReport;
use App\Report\Tour\EventReport;
use App\Report\Tour\TourReport;

class AdvancedBespokeController extends Controller
{
    public function create(string $type = 'order')
    {
        return view('pages.admin.report.new.form', ['report' => $this->getReport($type)]);
    }

    public function store(AdvancedReportRequest $request)
    {
        $generated = $this->generateReport($request);
        $report = new BespokeReport([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $generated::class,
            'fields' => $generated->getKeys(),
        ]);
        $report->save();
        return redirect()->route('reports.advanced.view', ['report' => $report,]);
    }

    public function view(BespokeReport $report)
    {
        return view('pages.admin.report.new.view', ['report' => $report,]);
    }

    public function edit(BespokeReport $report)
    {
        $highest = $this->getHighest($report);
        return view('pages.admin.report.new.form', ['report' => new $highest($report->fields), 'update' => $report,]);
    }

    public function update(AdvancedReportRequest $request, BespokeReport $report)
    {
        $generated = $this->generateReport($request);
        $report->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $generated::class,
            'fields' => $generated->getKeys(),
        ]);
        $report->save();
        return redirect()->route('reports.advanced.view', ['report' => $report,]);
    }

    public function delete(BespokeReport $report)
    {
        $report->delete();
        return redirect()->route('reports.all');
    }

    private function generateReport(AdvancedReportRequest $request): \App\Report\BespokeReport|null
    {
        /** @var \App\Report\BespokeReport|null $highest */
        $highest = null;
        $keys = [];
        foreach ($request->keys as $key => $value) {
            $type = $this->getReport(strtok($key, '_'));
            if ($type !== null) {
                $keys[] = $key;
                if ($type->getPriority() > ($highest?->getPriority() ?? 0)) {
                    $highest = $type;
                }
            }
        }
        if ($highest === null) return null;
        return new $highest($keys);
    }

    private function getReport(string $type)
    {
        return match ($type) {
            'event' => new EventReport(),
            'tour' => new TourReport(),
            'order' => new OrderReport(),
            default => null,
        };
    }

    private function getHighest(BespokeReport $report)
    {
        return match (get_class($report->getReport())) {
            EventReport::class,
            TourReport::class,
            OrderReport::class => OrderReport::class,
        };
    }
}
