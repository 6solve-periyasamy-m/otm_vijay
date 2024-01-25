<?php

namespace App\Http\Controllers\Admin\Flight;

use App\Http\Controllers\Controller;
use App\Models\Flight\Airline;
use Illuminate\Http\Request;

class AirlineController extends Controller
{
    public function create()
    {
        return view('pages.admin.flight.airline.form');
    }

    public function store(Request $request)
    {
        $request->validate(Airline::getValidationRules());
        $airline = Airline::create([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function edit(Airline $airline)
    {
        return view('pages.admin.flight.airline.form', ['airline' => $airline,]);
    }

    public function update(Request $request, Airline $airline)
    {
        $request->validate(Airline::getValidationRules($airline->id));
        $airline->update([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function destroy(Airline $airline)
    {
        $repo = $airline->repository;
        $repo->delete();
        return $repo->getReturnURL();
    }
}
