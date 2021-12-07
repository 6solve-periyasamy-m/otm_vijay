<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\TravelClass;
use Illuminate\Http\Request;

class TravelClassController extends Controller
{

    public function index()
    {
        return view('pages.models.travel_classes.table', ['travelClasses' => TravelClass::all(),]);
    }

    public function create()
    {
        return view('pages.models.travel_classes.create');
    }

    public function store(Request $request)
    {
        $request->validate(TravelClass::getValidationRules());
        $travelClass = TravelClass::create([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('travel-classes.view', ['travelClass' => $travelClass,]);
    }

    public function view(TravelClass $travelClass)
    {
        return view('pages.models.travel_classes.view', ['travelClass' => $travelClass,]);
    }

    public function edit(TravelClass $travelClass)
    {
        return view('pages.models.travel_classes.update', ['travelClass' => $travelClass,]);
    }

    public function update(Request $request, TravelClass $travelClass)
    {
        $request->validate(TravelClass::getValidationRules());
        $travelClass->update([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('travel-classes.view', ['travelClass' => $travelClass,]);
    }

    public function destroy(TravelClass $travelClass)
    {
        $travelClass->delete();
        return redirect()->route('travel-classes.all');
    }
}
