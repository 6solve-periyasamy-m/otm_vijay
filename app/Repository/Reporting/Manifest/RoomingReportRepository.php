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
use Settings;
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
            $row->purchase_currency = $orderComponent->tourComponent->repository->getCurrency()->code;
            $row->purchase = $orderComponent->tourComponent->repository->getPurchasePrice();
            $row->sales_currency = $orderAccommodation->group->orderCustomers[0]->order->currency?->code ?? Settings::currency()->code;
            $row->sales = $orderAccommodation->cost ?? $orderAccommodation->accommodation_inventory->sales_price;
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

    /**
     * @param HasRoomingList $roomingList
     * @return Collection
     */
    public static function generateCombineRoomingList(HasRoomingList $roomingList): Collection
    {
        $largest = 0;
        $groupedData = [];

        foreach ($roomingList->getRoomingList() as $orderAccommodation) {
            if ($orderAccommodation->cancelled) continue;

            $occupancy = $orderAccommodation->accommodation_inventory->roomType->maximum_occupancy;
            if ($largest < $occupancy) $largest = $occupancy;

            $travellers = $orderAccommodation->group->orderCustomers()->with('customer')->get();
            $travellerNames = $travellers->map(fn($t) => $t->customer?->first_name . ' ' . $t->customer?->last_name)->sort()->toArray();
            $travellerKey = implode('|', $travellerNames);

            $key = implode('|', [
                $orderAccommodation->accommodationInventoryTour->tour->name,
                $orderAccommodation->accommodationInventoryTour->tour->event?->name,
                $orderAccommodation->accommodation->name,
                $orderAccommodation->accommodation_inventory->roomType->name,
                $orderAccommodation->accommodation_inventory->boardType->name,
                $orderAccommodation->group->orderCustomers[0]->order->booking_reference,
                $travellerKey
            ]);

            if (!isset($groupedData[$key])) {
                $groupedData[$key] = [
                    'tour' => $orderAccommodation->accommodationInventoryTour->tour->name,
                    'event' => $orderAccommodation->accommodationInventoryTour->tour->event?->name,
                    'hotel' => $orderAccommodation->accommodation->name,
                    'room' => $orderAccommodation->accommodation_inventory->roomType->name,
                    'board' => $orderAccommodation->accommodation_inventory->boardType->name,
                    'from' => $orderAccommodation->accommodation_inventory->check_in,
                    'to' => $orderAccommodation->accommodation_inventory->check_out,
                    'reference' => $orderAccommodation->group->orderCustomers[0]->order->booking_reference,
                    'purchase' => $orderAccommodation->repository->getCostToCompany(),
                    'sales' => $orderAccommodation->cost ?? $orderAccommodation->accommodation_inventory->sales_price,
                    'occupancy' => $occupancy,
                    'occupants' => $travellers->count(),
                    'empty_beds' => $occupancy - $travellers->count(),
                    'travellers' => $travellers,
                ];
            } else {
                $groupedData[$key]['from'] = min($groupedData[$key]['from'], $orderAccommodation->accommodation_inventory->check_in);
                $groupedData[$key]['to'] = max($groupedData[$key]['to'], $orderAccommodation->accommodation_inventory->check_out);
                $groupedData[$key]['purchase'] += $orderAccommodation->repository->getCostToCompany();
                $groupedData[$key]['sales'] += $orderAccommodation->cost ?? $orderAccommodation->accommodation_inventory->sales_price;
            }
        }
        $dCol = collect();
        $dCol->data = collect(array_values($groupedData));
        $dCol->largest = $largest;
        return $dCol;
    }
}
