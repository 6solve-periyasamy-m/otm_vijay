<?php

namespace App\Http\Controllers\Admin\Flight;

use App\Http\Controllers\Controller;
use App\Models\Flight\Flight;
use App\Models\Flight\FlightInventory;
use App\Repository\Reporting\Manifest\FlightManifestRepository;
use Illuminate\Http\Request;

class FlightInventoryController extends Controller
{
    public function create(Flight $flight)
    {
        return view('pages.admin.flight.inventory.form', ['flight' => $flight,]);
    }

    public function store(Request $request, Flight $flight)
    {
        $request->validate(FlightInventory::getValidationRules());
        $inventory = FlightInventory::make([
            'travel_class_id' => $request->input('travel_class_id'),
            'flight_number' => $request->input('flight_number'),
            'check_in' => $request->input('check_in'),
            'departs_at' => $request->input('departs_at'),
            'arrives_at' => $request->input('arrives_at'),
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price') ?? 0,
            'sales_price' => $request->input('sales_price') ?? 0,
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
        ]);
        $flight->flightInventory()->save($inventory);
        return redirect()->route('flights.view', ['flight' => $flight, 'flightInventory' => $inventory,]);
    }

    public function manifest(Flight $flight, FlightInventory $inventory)
    {
        return FlightManifestRepository::viewReport($inventory->repository, 'flight-inventories.manifest.export', ['flight' => $flight, 'inventory' => $inventory]);
    }

    public function export(Flight $flight, FlightInventory $inventory, string $extension = 'xlsx')
    {
        return FlightManifestRepository::exportReport($inventory->repository, $extension);
    }

    public function edit(Flight $flight, FlightInventory $inventory)
    {
        return view('pages.admin.flight.inventory.form', ['flight' => $flight, 'inventory' => $inventory,]);
    }

    public function update(Request $request, Flight $flight, FlightInventory $inventory)
    {
        $request->validate(FlightInventory::getValidationRules());
        $inventory->update([
            'travel_class_id' => $request->input('travel_class_id'),
            'flight_number' => $request->input('flight_number'),
            'check_in' => $request->input('check_in'),
            'departs_at' => $request->input('departs_at'),
            'arrives_at' => $request->input('arrives_at'),
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price') ?? 0,
            'sales_price' => $request->input('sales_price') ?? 0,
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
        ]);
        return redirect()->route('flights.view', ['flight' => $flight,]);
    }

    public function destroy(Flight $flight, FlightInventory $inventory)
    {
        if ($inventory->flightInventoryTour()->count() > 0) {
            return back()->withErrors(trans('custom.used-in-tour', ['model' => 'Flight Inventory']));
        }
        $inventory->delete();
        return redirect()->route('flights.view', ['flight' => $flight,]);
    }

    public function duplicate(Flight $flight, FlightInventory $inventory)
    {
        $cloned = $inventory->replicate();
        $cloned->save();
        return redirect()->route('flight-inventories.edit', ['flight' => $flight, 'inventory' => $cloned,]);
    }
}
