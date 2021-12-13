<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Airline;
use Illuminate\Http\Request;

class AirlineController extends Controller
{

    public function index()
    {
        return view('pages.models.airlines.table', ['airlines' => Airline::all(),]);
    }

    public function create()
    {
        return view('pages.models.airlines.create');
    }

    public function store(Request $request)
    {
        $request->validate(Airline::getValidationRules());
        $airline = Airline::create([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('airlines.view', ['airline' => $airline,]);
    }

    public function view(Airline $airline)
    {
        return view('pages.models.airlines.view', ['airline' => $airline,]);
    }

    public function edit(Airline $airline)
    {
        return view('pages.models.airlines.update', ['airline' => $airline,]);
    }

    public function update(Request $request, Airline $airline)
    {
        $request->validate(Airline::getValidationRules());
        $airline->update([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('airlines.view', ['airline' => $airline,]);
    }

    public function destroy(Airline $airline)
    {
        $airline->delete();
        return redirect()->route('airlines.all');
    }
}
