<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use App\Models\TransportInventory;
use Illuminate\Http\Request;

class TransportInventoryController extends Controller
{

    public function index()
    {
        return view('pages.models.transport_inventories.table', ['transportInventories' => TransportInventory::all(),]);
    }

    public function create(Transport $transport)
    {
        return view('pages.models.transport_inventories.create', ['transport' => $transport,]);
    }

    public function store(Request $request, Transport $transport)
    {
        $request->validate(TransportInventory::getValidationRules());
        $transportInventory = TransportInventory::make([
            'travel_class_id' => $request->input('travel_class_id'),
            'departs_at' => $request->input('departs_at'),
            'departure_time_confirmed' => $request->input('departure_time_confirmed') === 'on' ? 1 : 0,
            'arrives_at' => $request->input('arrives_at'),
            'arrival_time_confirmed' => $request->input('arrival_time_confirmed') === 'on' ? 1 : 0,
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'sales_price' => $request->input('sales_price'),
            'notes' => $request->input('notes'),
        ]);
        $transport->transportInventory()->save($transportInventory);
        return redirect()->route('transports.view', ['transport' => $transport,]);
    }

    public function view(TransportInventory $transportInventory)
    {
        return view('pages.models.transport_inventories.view', ['transportInventory' => $transportInventory,]);
    }

    public function edit(Transport $transport, TransportInventory $transportInventory)
    {
        return view('pages.models.transport_inventories.update', ['transport' => $transport, 'transportInventory' => $transportInventory,]);
    }

    public function update(Request $request, Transport $transport, TransportInventory $transportInventory)
    {
        $request->validate(TransportInventory::getValidationRules());
        $transportInventory->update([
            'travel_class_id' => $request->input('travel_class_id'),
            'departs_at' => $request->input('departs_at'),
            'departure_time_confirmed' => $request->input('departure_time_confirmed') === 'on' ? 1 : 0,
            'arrives_at' => $request->input('arrives_at'),
            'arrival_time_confirmed' => $request->input('arrival_time_confirmed') === 'on' ? 1 : 0,
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'sales_price' => $request->input('sales_price'),
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('transports.view', ['transport' => $transport,]);
    }

    public function destroy(Transport $transport, TransportInventory $transportInventory)
    {
        if ($transportInventory->tourComponents()->count() > 0) {
            return back()->withErrors(trans('custom.used-in-tour', ['model' => 'Transport Inventory']));
        }
        $transportInventory->delete();
        return redirect()->route('transports.view', ['transport' => $transport,]);
    }

    public function duplicate(Transport $transport, TransportInventory $transportInventory)
    {
        $inventory = $transportInventory->replicate();
        $inventory->save();
        return redirect()->route('transport-inventories.edit', ['transport' => $transport, 'transportInventory' => $inventory,]);
    }
}
