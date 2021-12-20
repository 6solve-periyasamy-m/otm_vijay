<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\LocationType;
use Illuminate\Http\Request;

class LocationTypeController extends Controller
{

    public function index()
    {
        return view('pages.models.location_types.table', ['locationTypes' => LocationType::all(),]);
    }

    public function create()
    {
        return view('pages.models.location_types.create');
    }

    public function store(Request $request)
    {
        $request->validate(LocationType::getValidationRules());
        $locationType = LocationType::create([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('location-types.view', ['locationType' => $locationType,]);
    }

    public function view(LocationType $locationType)
    {
        return view('pages.models.location_types.view', ['locationType' => $locationType,]);
    }

    public function edit(LocationType $locationType)
    {
        return view('pages.models.location_types.update', ['locationType' => $locationType,]);
    }

    public function update(Request $request, LocationType $locationType)
    {
        $request->validate(LocationType::getValidationRules());
        $locationType->update([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('location-types.view', ['locationType' => $locationType,]);
    }

    public function destroy(LocationType $locationType)
    {
        $locationType->delete();
        return redirect()->route('location-types.all');
    }
}
