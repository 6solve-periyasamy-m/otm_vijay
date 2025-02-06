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
            $row->purchase = $orderAccommodation->repository->getCostToCompany();
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
    public static function generateGroupRoomingList(HasRoomingList $roomingList): Collection
    {
        $largest = 0;
        $grouped_data = [];
        foreach ($roomingList->getRoomingList() as $orderAccommodation) {
            if ($orderAccommodation->cancelled) continue;

            $occupancy = $orderAccommodation->accommodation_inventory->roomType->maximum_occupancy;
            if ($largest < $occupancy) $largest = $occupancy;

            $key = implode('|', [
                $orderAccommodation->accommodation->name,
                $orderAccommodation->accommodation_inventory->roomType->name,
                $orderAccommodation->accommodation_inventory->boardType->name,
                $orderAccommodation->group->orderCustomers[0]->order->booking_reference,
            ]);

            if (!isset($grouped_data[$key])) {
                $grouped_data[$key] = [
                    'tour' => $orderAccommodation->accommodationInventoryTour->tour->name,
                    'event' => $orderAccommodation->accommodationInventoryTour->tour->event?->name,
                    'hotel' => $orderAccommodation->accommodation->name,
                    'from' => $orderAccommodation->accommodation_inventory->check_in,
                    'to' => $orderAccommodation->accommodation_inventory->check_out,
                    'room' => $orderAccommodation->accommodation_inventory->roomType->name,
                    'board' => $orderAccommodation->accommodation_inventory->boardType->name,
                    'reference' => $orderAccommodation->group->orderCustomers[0]->order->booking_reference,
                    'travellers' => collect(),
                    'purchase' => 0,
                    'sales' => 0,
                    'occupancy' => $occupancy,
                    'occupants' => $orderAccommodation->group->orderCustomers()->count(),
                    'empty_beds' => $occupancy - $orderAccommodation->group->orderCustomers()->count(),
                    'travellers_groups' => [],
                ];
            }
            $grouped_data[$key]['purchase'] += $orderAccommodation->repository->getCostToCompany();
            $grouped_data[$key]['sales'] += $orderAccommodation->cost ?? $orderAccommodation->accommodation_inventory->sales_price;
            $grouped_data[$key]['to'] = max($grouped_data[$key]['to'], $orderAccommodation->accommodation_inventory->check_out);

            $travellers = $orderAccommodation->group->orderCustomers()->with('customer')->get();
            $grouped_data[$key]['travellers'] = $grouped_data[$key]['travellers']->merge($travellers);

            $travellers_group = $orderAccommodation->group->orderCustomers()->with('customer')->get()->map(function ($traveller) {
                return ($traveller->customer?->first_name ?? 'Redacted') . ' ' . ($traveller->customer?->last_name ?? 'Redacted');
            })->toArray();
            $grouped_data[$key]['travellers_groups'][] = implode(", ", $travellers_group);
            // $travellers = $orderAccommodation->group->orderCustomers()->with('customer')->get();
            // $grouped_data[$key]['travellers'] = $grouped_data[$key]['travellers']->merge($travellers);
        }

        foreach ($grouped_data as &$record) {
            $record['travellers_groups'] = implode(' | ', $record['travellers_groups']);
        }
        $data = collect(array_values($grouped_data));
        $dCol = collect();
        $dCol->data = $data;
        $dCol->largest = $largest;
        return $dCol;
    }
}
