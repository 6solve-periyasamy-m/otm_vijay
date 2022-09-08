<?php

namespace App\Exports;

use App\Repository\Interfaces\HasRoomingList;
use App\Repository\Reporting\RoomingReportRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RoomingReportExport implements FromView
{
    private HasRoomingList $roomingList;

    public function __construct(HasRoomingList $roomingList)
    {
        $this->roomingList = $roomingList;
    }

    public function view(): View
    {
        return view('partials.reports.tables.rooming', ['data' => RoomingReportRepository::generateRoomingList($this->roomingList),]);
    }
}
