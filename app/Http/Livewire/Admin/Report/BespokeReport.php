<?php

namespace App\Http\Livewire\Admin\Report;

use App\Http\Livewire\Abstract\ExportableDatatable;

class BespokeReport extends ExportableDatatable
{
    public \App\Models\System\BespokeReport $report;

    public function builder()
    {
        \Log::info($this->report->getReport()->getQuery()->toSql());
        return $this->report->getReport()->getQuery();
    }

    public function columns()
    {
        return $this->report->getReport()->getColumns();
    }
}