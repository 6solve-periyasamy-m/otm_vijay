<?php

namespace App\Exports;

use App\Repository\Interfaces\HasRoomingList;
use App\Repository\Reporting\RoomingReportRepository;
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
        return view('partials.reports.tables.rooming-export', ['data' => RoomingReportRepository::generateRoomingList($this->roomingList), 'notes' => $this->showNotes,]);
    }
}
