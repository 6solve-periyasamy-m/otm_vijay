<?php

namespace App\Repository\Reporting\Manifest;

use App\Exports\Manifest\OrderManifestExport;
use App\Models\Order\Order;
use App\Repository\Interfaces\Manifest\HasOrderManifest;
use App\Repository\Reporting\Manifest\Storage\OrderRow;
use Excel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderManifestRepository implements HasOrderManifest
{
    public function __construct(private HasOrderManifest|null $manifest)
    {
        if ($manifest === null) {
            $this->manifest = $this;
        }
    }

    /**
     * @return OrderRow[]
     */
    public function getRows(): array
    {
        $rows = [];
        foreach ($this->manifest->getOrderManifest() as $order) {
            $rows = [
                ...$rows,
                ...OrderRow::fromOrder($order),
            ];
        }
        return $rows;
    }

    public function view(string $export, array $data = []): View|Application|Factory
    {
        return view('pages.reports.view', [
            'tableView' => 'partials.reports.tables.manifest.order',
            'data' => $this->getRows(),
            'title' => 'Activity Manifest',
            'xlsxExport' => route($export, ['extension' => 'xlsx', ...$data]),
            'csvExport' => route($export, ['extension' => 'csv', ...$data]),
        ]);
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(string $extension): BinaryFileResponse
    {
        return Excel::download(new OrderManifestExport($this->manifest), 'product-analysis.' . $extension);
    }

    public function getOrderManifest(): Collection|array
    {
        return Order::with(self::getEagerLoads())->get();
    }

    public static function getEagerLoads(): array
    {
        return [
            'tour',
            'tour.event',
            'leadBooker',
            'leadBooker.customer',
            'orderMerchandise',
            'orderMerchandise.tourComponent',
            'orderMerchandise.tourComponent.inventory',
            'orderMerchandise.tourComponent.inventory.component',
            'orderActivities',
            'orderActivities.tourComponent',
            'orderActivities.tourComponent.inventory',
            'orderActivities.tourComponent.inventory.component',
            'orderFlights',
            'orderFlights.tourComponent',
            'orderFlights.tourComponent.inventory',
            'orderFlights.tourComponent.inventory.component',
            'orderTransport',
            'orderTransport.tourComponent',
            'orderTransport.tourComponent.inventory',
            'orderTransport.tourComponent.inventory.component',
        ];
    }
}
