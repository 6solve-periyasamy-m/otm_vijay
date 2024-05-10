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
        $flightInventory = FlightInventory::make([
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
        $flight->flightInventory()->save($flightInventory);
        return redirect()->route('flights.view', ['flight' => $flight, 'flightInventory' => $flightInventory,]);
    }

    public function manifest(Flight $flight, FlightInventory $flightInventory)
    {
        return FlightManifestRepository::viewReport($flightInventory->repository, 'flight-inventories.manifest.export', ['flight' => $flight, 'flightInventory' => $flightInventory]);
    }

    public function export(Flight $flight, FlightInventory $flightInventory, string $extension = 'xlsx')
    {
        return FlightManifestRepository::exportReport($flightInventory->repository, $extension);
    }

    public function edit(Flight $flight, FlightInventory $flightInventory)
    {
        return view('pages.admin.flight.inventory.form', ['flight' => $flight, 'inventory' => $flightInventory,]);
    }

    public function update(Request $request, Flight $flight, FlightInventory $flightInventory)
    {
        $request->validate(FlightInventory::getValidationRules());
        $flightInventory->update([
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

    public function destroy(Flight $flight, FlightInventory $flightInventory)
    {
        if ($flightInventory->flightInventoryTour()->count() > 0) {
            return back()->withErrors(trans('custom.used-in-tour', ['model' => 'Flight Inventory']));
        }
        $flightInventory->delete();
        return redirect()->route('flights.view', ['flight' => $flight,]);
    }

    public function duplicate(Flight $flight, FlightInventory $flightInventory)
    {
        $inventory = $flightInventory->replicate();
        $inventory->save();
        return redirect()->route('flight-inventories.edit', ['flight' => $flight, 'inventory' => $inventory,]);
    }
}
