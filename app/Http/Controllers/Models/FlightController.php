<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FlightController extends Controller
{

    public function index()
    {
        return view('pages.models.flights.table', ['flights' => Flight::all(),]);
    }

    public function create()
    {
        return view('pages.models.flights.create');
    }

    public function store(Request $request)
    {
        $request->validate(Flight::getValidationRules());
        $flight = Flight::create([
            'airline_id' => $request->input('airline_id'),
            'departure_airport_id' => $request->input('departure_airport_id'),
            'arrival_airport_id' => $request->input('arrival_airport_id'),
            'is_domestic' => $request->input('is_domestic') === 'on' ? 1 : 0,
            'currency_id' => $request->input('currency_id'),
            'notes' => $request->input('notes'),
            'available_after' => $request->input('available_after'),
        ]);
        if ($request->has('image') && $request->file('image') != null) {
            $flight->image_url = $request->file('image')->storePublicly('uploads/images');
        }
        return redirect()->route('flights.view', ['flight' => $flight,]);
    }

    public function view(Flight $flight)
    {
        return view('pages.components.flight', ['flight' => $flight,]);
    }

    public function edit(Flight $flight)
    {
        return view('pages.models.flights.update', ['flight' => $flight,]);
    }

    public function update(Request $request, Flight $flight)
    {
        $request->validate(Flight::getValidationRules());
        $flight->update([
            'airline_id' => $request->input('airline_id'),
            'departure_airport_id' => $request->input('departure_airport_id'),
            'arrival_airport_id' => $request->input('arrival_airport_id'),
            'is_domestic' => $request->input('is_domestic') === 'on' ? 1 : 0,
            'currency_id' => $request->input('currency_id'),
            'notes' => $request->input('notes'),
            'available_after' => $request->input('available_after'),
        ]);
        if ($request->has('image') && $request->file('image') != null) {
            if (isset($flight->image_url)) {
                File::delete(public_path($flight->image_url));
            }
            $flight->image_url = $request->file('image')->storePublicly('uploads/images');
        }

        return redirect()->route('flights.view', ['flight' => $flight,]);
    }

    public function destroy(Flight $flight)
    {
        foreach ($flight->flightInventory as $inventory) {
            if ($inventory->flightInventoryTour()->count() > 0) {
                return back()->withErrors(trans('custom.used-in-tour', ['model' => 'Flight']));
            }
        }
        $flight->delete();
        return redirect()->route('flights.all');
    }

    public function createReturn(Flight $flight)
    {
        $returnFlight = $flight->replicate();
        $depart = $flight->arrival_airport_id;
        $arrival = $flight->departure_airport_id;
        $returnFlight->departure_airport_id = $depart;
        $returnFlight->arrival_airport_id = $arrival;
        $returnFlight->save();
        return redirect()->route('flights.edit', ['flight' => $returnFlight,]);
    }
}
