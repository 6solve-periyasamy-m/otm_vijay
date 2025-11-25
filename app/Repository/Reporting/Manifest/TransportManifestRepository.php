<?php

namespace App\Repository\Reporting\Manifest;

use App\Exports\Manifest\TransportManifestExport;
use App\Models\Order\Component\OrderTransport;
use App\Repository\Interfaces\Manifest\HasTransportManifest;
use Excel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TransportManifestRepository implements HasTransportManifest
{
    public function getTransportManifest(): Collection|array
    {
        return OrderTransport::with(self::getRelations())->get();
    }

    public static function getRelations(): array
    {
        return [
            'orderCustomer',
            'orderCustomer.order',
            'orderCustomer.customer',
            'tourComponent',
            'tourComponent.inventory',
            'tourComponent.inventory.component',
            'tourComponent.inventory.travelClass',
            'tourComponent.inventory.component.departureAddress',
            'tourComponent.inventory.component.arrivalAddress',
            'tourComponent.inventory.component.transportType',
            'tourComponent.inventory.component.operator',
            'orderCustomer.order.currency',
            'tourComponent.inventory.currency',
            'tourComponent.inventory.component.currency',
        ];
    }


    public static function viewReport(HasTransportManifest $manifest, string $export, array $data = []): Application|Factory|View
    {
        return view('pages.reports.view', [
            'tableView' => 'partials.reports.tables.manifest.transport',
            'data' => self::generateReport($manifest),
            'title' => 'Transport Manifest',
            'xlsxExport' => route($export, ['extension' => 'xlsx', ...$data]),
            'csvExport' => route($export, ['extension' => 'csv', ...$data]),
        ]);
    }

    public static function exportReport(HasTransportManifest $manifest, string $extension): BinaryFileResponse
    {
        return Excel::download(new TransportManifestExport($manifest), 'transports.' . $extension);
    }

    public static function generateReport(HasTransportManifest $manifest): array
    {
        $data = [];
        foreach ($manifest->getTransportManifest() as $orderComponent) {
            if ($orderComponent->cancelled) continue;
            $row = collect();
            $row->reference = $orderComponent->orderCustomer->order->booking_reference;
            $row->event = $orderComponent->orderCustomer->order->tour?->event?->name;
            $row->customer = $orderComponent->orderCustomer->customer_name;
            $row->orderInternal = $orderComponent->orderCustomer->order->internal_notes;
            $row->orderExternal = $orderComponent->orderCustomer->order->external_notes;
            $row->travellers = $orderComponent->orderCustomer->order->orderCustomers()->where('is_travelling','=', true)->count();
            $row->passport = $orderComponent->orderCustomer->customer->passport_first_name . ' ' . $orderComponent->orderCustomer->customer->passport_middle_name . ' ' . $orderComponent->orderCustomer->customer->passport_last_name;
            $row->transport = $orderComponent->transportInventoryTour->inventory->component->name;
            $row->operator = $orderComponent->tourComponent->inventory->component->operator->name;
            $row->departure = $orderComponent->tourComponent->inventory->component->departureAddress;
            $row->arrival = $orderComponent->tourComponent->inventory->component->arrivalAddress;
            $row->number = $orderComponent->tourComponent->inventory->transport_number;
            $row->ticket = $orderComponent->tourComponent->inventory->travelClass->name;
            $row->component = $orderComponent->tourComponent->tour_component_type;
            $row->start = $orderComponent->repository->getStartTime();
            $row->end = $orderComponent->repository->getEndTime();
            $row->purchase_currency = $orderComponent->tourComponent->repository->getCurrency()->code;
            $row->purchase = $orderComponent->tourComponent->repository->getPurchasePrice();
            $row->sales_currency = $orderComponent->orderCustomer->order->currency?->code ?? \Settings::currency()->code;
            $row->sales = $orderComponent->cost ?? $orderComponent->tourComponent->inventory->sales_price;
            $row->notes = $orderComponent->orderCustomer->transport_notes;
            $data[] = $row;
        }
        return $data;
    }
}
