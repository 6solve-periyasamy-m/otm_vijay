<?php

namespace App\Repository\Reporting\Manifest;

use App\Exports\Manifest\FlightManifestExport;
use App\Models\Order\Component\OrderFlight;
use App\Repository\Interfaces\Manifest\HasFlightManifest;
use Excel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FlightManifestRepository implements HasFlightManifest
{
    public function getFlightManifest(): Collection|array
    {
        return OrderFlight::with(self::getRelations())->get();
    }

    public static function getRelations(): array
    {
        return [
            'orderCustomer',
            'orderCustomer.order',
            'orderCustomer.customer',
            'tourComponent',
            'tourComponent.tour',
            'tourComponent.tour.event',
            'tourComponent.inventory',
            'tourComponent.inventory.component',
            'tourComponent.inventory.travelClass',
            'tourComponent.inventory.component.departureAirport',
            'tourComponent.inventory.component.arrivalAirport',
            'tourComponent.inventory.component.airline',
            'orderCustomer.order.currency',
            'tourComponent.inventory.currency',
            'tourComponent.inventory.component.currency',
        ];
    }


    public static function viewReport(HasFlightManifest $manifest, string $export, array $data = []): Application|Factory|View
    {
        return view('pages.reports.view', [
            'tableView' => 'partials.reports.tables.manifest.flight',
            'data' => self::generateReport($manifest),
            'title' => 'Flight Manifest',
            'xlsxExport' => route($export, ['extension' => 'xlsx', ...$data]),
            'csvExport' => route($export, ['extension' => 'csv', ...$data]),
        ]);
    }

    public static function exportReport(HasFlightManifest $manifest, string $extension): BinaryFileResponse
    {
        return Excel::download(new FlightManifestExport($manifest), 'flights.' . $extension);
    }

    public static function generateReport(HasFlightManifest $manifest): array
    {
        $data = [];
        foreach ($manifest->getFlightManifest() as $orderComponent) {
            if ($orderComponent->cancelled) continue;
            $row = collect();
            $row->reference = $orderComponent->orderCustomer->order->booking_reference;
            $row->customer = $orderComponent->orderCustomer->customer_name;
            $row->orderInternal = $orderComponent->orderCustomer->order->internal_notes;
            $row->orderExternal = $orderComponent->orderCustomer->order->external_notes;
            $row->passport = $orderComponent->orderCustomer->customer->passport_first_name . ' ' . $orderComponent->orderCustomer->customer->passport_middle_name . ' ' . $orderComponent->orderCustomer->customer->passport_last_name;
            $row->airline = $orderComponent->tourComponent->inventory->component->airline->name;
            $row->number = $orderComponent->flight_number;
            $row->tour = $orderComponent->tourComponent->tour->name;
            $row->event = $orderComponent->tourComponent->tour->event?->name;
            $row->departure = $orderComponent->tourComponent->inventory->component->departureAirport;
            $row->arrival = $orderComponent->tourComponent->inventory->component->arrivalAirport;
            $row->ticket = $orderComponent->tourComponent->inventory->travelClass->name;
            $row->component = $orderComponent->tourComponent->tour_component_type;
            $row->start = $orderComponent->tourComponent->inventory->departs_at;
            $row->end = $orderComponent->tourComponent->inventory->arrives_at;
            $row->purchase_currency = $orderComponent->tourComponent->repository->getCurrency()->code;
            $row->purchase = $orderComponent->tourComponent->repository->getPurchasePrice();
            $row->sales_currency = $orderComponent->orderCustomer->order->currency?->code ?? \Settings::currency()->code;
            $row->sales = $orderComponent->cost ?? $orderComponent->tourComponent->inventory->sales_price;
            $row->notes = $orderComponent->orderCustomer->flight_notes;
            $data[] = $row;
        }
        return $data;
    }
}
