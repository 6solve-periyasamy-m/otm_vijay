<?php

namespace App\Repository\Reporting\Manifest;

use App\Exports\Manifest\MerchandiseManifestExport;
use App\Models\Order\Component\OrderMerchandise;
use App\Repository\Interfaces\Manifest\HasMerchandiseManifest;
use Excel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MerchandiseManifestRepository implements HasMerchandiseManifest
{
    public function getMerchandiseManifest(): Collection|array
    {
        return OrderMerchandise::with(self::getRelations())->get();
    }

    public static function getRelations(): array
    {
        return [
            'orderCustomer',
            'orderCustomer.order',
            'orderCustomer.customer',
            'tourComponent',
            'tourComponent.inventory',
            'tourComponent.inventory.variant',
            'tourComponent.inventory.size',
            'tourComponent.inventory.component.type',
        ];
    }


    public static function viewReport(HasMerchandiseManifest $roomingList, string $export, array $data = []): Application|Factory|View
    {
        return view('pages.reports.view', [
            'tableView' => 'partials.reports.tables.manifest.merchandise',
            'data' => self::generateReport($roomingList),
            'title' => 'Merchandise Manifest',
            'xlsxExport' => route($export, ['extension' => 'xlsx', ...$data]),
            'csvExport' => route($export, ['extension' => 'csv', ...$data]),
        ]);
    }

    public static function exportReport(HasMerchandiseManifest $manifest, string $extension): BinaryFileResponse
    {
        return Excel::download(new MerchandiseManifestExport($manifest), 'merchandise.' . $extension);
    }

    public static function generateReport(HasMerchandiseManifest $manifest): array
    {
        $data = [];
        foreach ($manifest->getMerchandiseManifest() as $orderComponent) {
            if ($orderComponent->cancelled) continue;
            $row = collect();
            $row->reference = $orderComponent->orderCustomer->order->booking_reference;
            $row->customer = $orderComponent->orderCustomer->customer_name;
            $row->email = $orderComponent->orderCustomer->customer->email_address;
            $row->merchandise = $orderComponent->tourComponent->inventory->component->name;
            $row->passport = $orderComponent->orderCustomer->customer->passport_first_name . ' ' . $orderComponent->orderCustomer->customer->passport_middle_name . ' ' . $orderComponent->orderCustomer->customer->passport_last_name;
            $row->type = $orderComponent->tourComponent->inventory->component->type->name;
            $row->variant = $orderComponent->tourComponent->inventory->variant->name;
            $row->size = $orderComponent->tourComponent->inventory->size->name;
            $row->fulfilled = $orderComponent->fulfilled;
            $row->component = $orderComponent->tourComponent->tour_component_type;
            $row->purchase = $orderComponent->repository->getCostToCompany();
            $row->sales = $orderComponent->cost ?? $orderComponent->tourComponent->inventory->sales_price;
            $data[] = $row;
        }
        return $data;
    }
}
