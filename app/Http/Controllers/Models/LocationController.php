<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    public function index()
    {
        return view('pages.models.locations.table', ['locations' => Location::all(),]);
    }

    public function create()
    {
        return view('pages.models.locations.create');
    }

    public function store(Request $request)
    {
        $location = Location::create([
            'region_id' => $request->input('region_id'),
            'location_type_id' => $request->input('location_type_id'),
            'name' => $request->input('name'),
            'address' => $request->input('address'),
        ]);
        return redirect()->route('locations.view', ['location' => $location,]);
    }

    public function view(Location $location)
    {
        return view('pages.models.locations.view', ['location' => $location,]);
    }

    public function edit(Location $location)
    {
        return view('pages.models.locations.update', ['location' => $location,]);
    }

    public function update(Request $request, Location $location)
    {
        $location->update([
            'region_id' => $request->input('region_id'),
            'location_type_id' => $request->input('location_type_id'),
            'name' => $request->input('name'),
            'address' => $request->input('address'),
        ]);
        return redirect()->route('locations.view', ['location' => $location,]);
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.all');
    }
}
