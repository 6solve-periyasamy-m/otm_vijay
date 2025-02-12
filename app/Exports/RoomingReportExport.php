<?php

namespace App\Exports;

use App\Repository\Interfaces\Manifest\HasRoomingList;
use App\Repository\Reporting\Manifest\RoomingReportRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RoomingReportExport implements FromView
{
    private HasRoomingList $roomingList;
    private bool $showNotes;

    public function __construct(HasRoomingList $roomingList, bool $showNotes = true)
    {
        $this->roomingList = $roomingList;
        $this->showNotes = $showNotes;
    }

    public function view(): View
    {
        return view('partials.reports.tables.rooming-export', ['data' => RoomingReportRepository::generateCombineRoomingList($this->roomingList), 'notes' => $this->showNotes,]);
    }
}
