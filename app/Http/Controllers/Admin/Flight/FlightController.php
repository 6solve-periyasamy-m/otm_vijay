<?php

namespace App\Http\Controllers\Admin\Flight;

use App\Http\Controllers\Controller;
use App\Models\Flight\Flight;
use App\Repository\Reporting\Manifest\FlightManifestRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FlightController extends Controller
{

    public function index(Request $request)
    {
        $archived = $request->archived ?? false;
        return view('pages.admin.flight.table', ['archived' => $archived,]);
    }

    public function create()
    {
        return view('pages.admin.flight.form');
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
            'internal_notes' => $request->input('notes'),
            'available_from' => $request->input('available_from'),
        ]);
        if ($request->has('image') && $request->file('image') != null) {
            $flight->image_url = $request->file('image')->storePublicly('uploads/images');
        }
        $flight->save();
        return redirect()->route('flights.view', ['flight' => $flight,]);
    }

    public function view(Flight $flight)
    {
        return view('pages.admin.flight.view', ['flight' => $flight,]);
    }

    public function manifest(Flight $flight)
    {
        return FlightManifestRepository::viewReport($flight->repository, 'flights.manifest.export', ['flight' => $flight,]);
    }

    public function export(Flight $flight, string $extension = 'xlsx')
    {
        return FlightManifestRepository::exportReport($flight->repository, $extension);
    }

    public function edit(Flight $flight)
    {
        return view('pages.admin.flight.form', ['flight' => $flight,]);
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
            'internal_notes' => $request->input('notes'),
            'available_from' => $request->input('available_from'),
        ]);
        if ($request->has('image') && $request->file('image') != null) {
            if (isset($flight->image_url)) {
                File::delete(public_path($flight->image_url));
            }
            $flight->image_url = $request->file('image')->storePublicly('uploads/images');
        }
        $flight->save();
        return redirect()->route('flights.view', ['flight' => $flight,]);
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

    public function archive(Flight $flight): RedirectResponse
    {
        $flight->archived = !$flight->archived;
        $flight->save();

        if (!$flight->archived) {
            return redirect()->route('flights.view', ['flight' => $flight,]);
        }

        return redirect()->route('flights.all');
    }
}
