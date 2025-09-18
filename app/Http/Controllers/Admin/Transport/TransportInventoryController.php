<?php

namespace App\Http\Controllers\Admin\Transport;

use App\Exceptions\CannotDeleteException;
use App\Http\Controllers\Controller;
use App\Models\Transport\Transport;
use App\Models\Transport\TransportInventory;
use App\Repository\Reporting\Manifest\TransportManifestRepository;
use Illuminate\Http\Request;

class TransportInventoryController extends Controller
{

    public function create(Transport $transport)
    {
        $this->authorize('self-child-access', [$transport, TransportInventory::class]);
        return view('pages.admin.transport.inventory.form', ['transport' => $transport,]);
    }

    public function store(Request $request, Transport $transport)
    {
        $this->authorize('self-child-access', [$transport, TransportInventory::class]);
        $request->validate(TransportInventory::getValidationRules());
        $inventory = TransportInventory::make([
            'travel_class_id' => $request->input('travel_class_id'),
            'transport_occupancy_id' => $request->input('transport_occupancy_id'),
            'departs_at' => $request->input('departs_at'),
            'departure_time_confirmed' => $request->input('departure_time_confirmed') === 'on' ? 1 : 0,
            'arrives_at' => $request->input('arrives_at'),
            'arrival_time_confirmed' => $request->input('arrival_time_confirmed') === 'on' ? 1 : 0,
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price') ?? 0,
            'sales_price' => $request->input('sales_price') ?? 0,
            'currency_id' => $request->input('currency_id'),
            'transport_number' => $request->input('transport_number'),
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
        ]);
        $transport->transportInventory()->save($inventory);
        return redirect()->route('transports.view', ['transport' => $transport,]);
    }

    public function manifest(Transport $transport, TransportInventory $inventory)
    {
        return TransportManifestRepository::viewReport($inventory->repository, 'transport-inventories.manifest.export', ['transport' => $transport, 'inventory' => $inventory]);
    }

    public function export(Transport $transport, TransportInventory $inventory, string $extension = 'xlsx')
    {
        return TransportManifestRepository::exportReport($inventory->repository, $extension);
    }

    public function edit(Transport $transport, TransportInventory $inventory)
    {
        return view('pages.admin.transport.inventory.form', ['transport' => $transport, 'inventory' => $inventory,]);
    }

    public function update(Request $request, Transport $transport, TransportInventory $inventory)
    {
        $request->validate(TransportInventory::getValidationRules());
        $inventory->update([
            'travel_class_id' => $request->input('travel_class_id'),
            'transport_occupancy_id' => $request->input('transport_occupancy_id'),
            'departs_at' => $request->input('departs_at'),
            'departure_time_confirmed' => $request->input('departure_time_confirmed') === 'on' ? 1 : 0,
            'arrives_at' => $request->input('arrives_at'),
            'arrival_time_confirmed' => $request->input('arrival_time_confirmed') === 'on' ? 1 : 0,
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price') ?? 0,
            'currency_id' => $request->input('currency_id'),
            'sales_price' => $request->input('sales_price') ?? 0,
            'transport_number' => $request->input('transport_number'),
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
        ]);
        return redirect()->route('transports.view', ['transport' => $transport,]);
    }

    public function destroy(Transport $transport, TransportInventory $inventory)
    {
        try {
            $inventory->repository->delete();
        } catch (CannotDeleteException $e) {
            return back()->withErrors($e->getMessage());
        }
        return redirect()->route('transports.view', ['transport' => $transport,]);
    }

    public function duplicate(Transport $transport, TransportInventory $inventory)
    {
        $cloned = $inventory->replicate();
        $cloned->save();
        return redirect()->route('transport-inventories.edit', ['transport' => $transport, 'inventory' => $cloned,]);
    }
}
