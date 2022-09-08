<?php

namespace App\Repository\Reporting;

use App\Exports\OrderReminderReportExport;
use App\Exports\RoomingReportExport;
use App\Models\Order\Component\OrderAccommodation;
use App\Repository\Interfaces\HasRoomingList;
use Excel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RoomingReportRepository implements HasRoomingList
{
    public function getRoomingList(): Collection|array
    {
        return OrderAccommodation::with(
            'group',
            'group.orderCustomers',
            'group.orderCustomers',
            'accommodationInventoryTour',
            'accommodationInventoryTour.inventory',
            'accommodationInventoryTour.accommodationInventory.accommodation',
            'accommodationInventoryTour.accommodationInventory.roomType',
            'accommodationInventoryTour.accommodationInventory.boardType',
        )->get();
    }

    public static function viewReport(HasRoomingList $roomingList, string $export, array $data = []): Factory|View|Application
    {
        return view('pages.reports.view', [
            'tableView' => 'partials.reports.tables.rooming',
            'data' => self::generateRoomingList($roomingList),
            'title' => 'Rooming Report',
            'xlsxExport' => route($export, ['extension' => 'xlsx', ...$data]),
            'csvExport' => route($export, ['extension' => 'csv', ...$data]),
        ]);
    }

    public static function exportReport(HasRoomingList $roomingList, string $extension): BinaryFileResponse
    {
        return Excel::download(new RoomingReportExport($roomingList), 'rooming.' . $extension);
    }

    /**
     * @param HasRoomingList $roomingList
     * @return array
     */
    public static function generateRoomingList(HasRoomingList $roomingList): array
    {
        $data = [];
        foreach ($roomingList->getRoomingList() as $orderAccommodation) {
            $row = collect();
            $row->tour = $orderAccommodation->accommodationInventoryTour->tour->name;
            $row->hotel = $orderAccommodation->accommodation->name;
            $row->from = $orderAccommodation->accommodation_inventory->check_in;
            $row->to = $orderAccommodation->accommodation_inventory->check_out;
            $row->room = $orderAccommodation->accommodation_inventory->roomType->name;
            $row->board = $orderAccommodation->accommodation_inventory->boardType->name;
            $row->travellers = $orderAccommodation->group->orderCustomers()->with('customer')->get();
            $row->occupants = $orderAccommodation->group->orderCustomers()->count();
            $data[] = $row;
        }
        return $data;
    }
}
