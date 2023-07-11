<?php

namespace App\Repository\Reporting\Manifest;

use App\Exports\RoomingReportExport;
use App\Models\Order\Component\OrderAccommodation;
use App\Repository\Interfaces\Manifest\HasRoomingList;
use Excel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RoomingReportRepository implements HasRoomingList
{
    public function getRoomingList(): Collection|array
    {
        return OrderAccommodation::with(
            'group',
            'group.orderCustomers',
            'group.orderCustomers.order',
            'accommodationInventoryTour',
            'accommodationInventoryTour.inventory',
            'accommodationInventoryTour.accommodationInventory.accommodation',
            'accommodationInventoryTour.accommodationInventory.roomType',
            'accommodationInventoryTour.accommodationInventory.boardType',
        )->get();
    }

    public static function viewReport(HasRoomingList $roomingList, string $export, bool $notes = true, array $data = []): Factory|View|Application
    {
        return view('pages.reports.view', [
            'tableView' => 'partials.reports.tables.rooming',
            'data' => self::generateRoomingList($roomingList),
            'title' => 'Rooming Report',
            'notes' => $notes,
            'xlsxExport' => route($export, ['extension' => 'xlsx', 'notes' => $notes, ...$data]),
            'csvExport' => route($export, ['extension' => 'csv', 'notes' => $notes, ...$data]),
        ]);
    }

    public static function exportReport(HasRoomingList $roomingList, string $extension, bool $notes = true): BinaryFileResponse
    {
        return Excel::download(new RoomingReportExport($roomingList, $notes), 'rooming.' . $extension);
    }

    /**
     * @param HasRoomingList $roomingList
     * @return Collection
     */
    public static function generateRoomingList(HasRoomingList $roomingList): Collection
    {
        $largest = 0;
        $data = [];
        foreach ($roomingList->getRoomingList() as $orderAccommodation) {
            if ($orderAccommodation->cancelled) continue;
            $occupancy = $orderAccommodation->accommodation_inventory->roomType->maximum_occupancy;
            if ($largest < $occupancy) $largest = $occupancy;
            $row = collect();
            $row->tour = $orderAccommodation->accommodationInventoryTour->tour->name;
            $row->event = $orderAccommodation->accommodationInventoryTour->tour->event?->name;
            $row->hotel = $orderAccommodation->accommodation->name;
            $row->from = $orderAccommodation->accommodation_inventory->check_in;
            $row->to = $orderAccommodation->accommodation_inventory->check_out;
            $row->room = $orderAccommodation->accommodation_inventory->roomType->name;
            $row->board = $orderAccommodation->accommodation_inventory->boardType->name;
            $row->reference = $orderAccommodation->group->orderCustomers[0]->order->booking_reference;
            $row->travellers = $orderAccommodation->group->orderCustomers()->with('customer')->get();
            $row->occupancy = $occupancy;
            $row->occupants = $orderAccommodation->group->orderCustomers()->count();
            $row->empty_beds = $occupancy - $row->occupants;
            $data[] = $row;
        }
        $dCol = collect();
        $dCol->data = $data;
        $dCol->largest = $largest;
        return $dCol;
    }
}
