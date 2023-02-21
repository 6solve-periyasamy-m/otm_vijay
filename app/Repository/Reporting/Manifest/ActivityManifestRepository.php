<?php

namespace App\Repository\Reporting\Manifest;

use App\Exports\Manifest\ActivityManifestExport;
use App\Models\Order\Component\OrderActivity;
use App\Repository\Interfaces\Manifest\HasActivityManifest;
use Excel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ActivityManifestRepository implements HasActivityManifest
{
    public function getActivityManifest(): Collection|array
    {
        return OrderActivity::with(self::getRelations())->get();
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
            'tourComponent.inventory.ticketType',
            'tourComponent.inventory.component.activityType',
        ];
    }


    public static function viewReport(HasActivityManifest $roomingList, string $export, array $data = []): Application|Factory|View
    {
        return view('pages.reports.view', [
            'tableView' => 'partials.reports.tables.manifest.activity',
            'data' => self::generateReport($roomingList),
            'title' => 'Activity Manifest',
            'xlsxExport' => route($export, ['extension' => 'xlsx', ...$data]),
            'csvExport' => route($export, ['extension' => 'csv', ...$data]),
        ]);
    }

    public static function exportReport(HasActivityManifest $manifest, string $extension): BinaryFileResponse
    {
        return Excel::download(new ActivityManifestExport($manifest), 'rooming.' . $extension);
    }

    public static function generateReport(HasActivityManifest $manifest): array
    {
        $data = [];
        foreach ($manifest->getActivityManifest() as $orderComponent) {
            if ($orderComponent->cancelled) continue;
            $row = collect();
            $row->reference = $orderComponent->orderCustomer->order->booking_reference;
            $row->customer = $orderComponent->orderCustomer->customer_name;
            $row->activity = $orderComponent->tourComponent->inventory->component->name;
            $row->type = $orderComponent->tourComponent->inventory->component->activityType->name;
            $row->ticket = $orderComponent->tourComponent->inventory->ticketType->name;
            $row->component = $orderComponent->tourComponent->tour_component_type;
            $row->start = $orderComponent->tourComponent->inventory->starts_at;
            $row->end = $orderComponent->tourComponent->inventory->ends_at;
            $row->notes = $orderComponent->orderCustomer->activity_notes;
            $data[] = $row;
        }
        return $data;
    }
}
