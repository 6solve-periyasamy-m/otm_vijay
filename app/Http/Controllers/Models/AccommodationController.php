<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{

    public function index()
    {
        return view('pages.models.accommodations.table', ['accommodations' => Accommodation::all(),]);
    }

    public function create()
    {
        return view('pages.models.accommodations.create');
    }

    public function store(Request $request)
    {
        $request->validate(Accommodation::RULES);
        $accommodation = Accommodation::create([
            'region_id' => $request->input('region_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'audit_date' => $request->input('audit_date'),
            'address' => $request->input('address'),
            'currency' => $request->input('currency'),
        ]);
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function view(Accommodation $accommodation)
    {
        return view('pages.components.accommodation', ['accommodation' => $accommodation, ]);
    }

    public function edit(Accommodation $accommodation)
    {
        return view('pages.models.accommodations.update', ['accommodation' => $accommodation,]);
    }

    public function update(Request $request, Accommodation $accommodation)
    {
        $request->validate(Accommodation::RULES);
        $accommodation->update([
            'region_id' => $request->input('region_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'audit_date' => $request->input('audit_date'),
            'address' => $request->input('address'),
            'currency' => $request->input('currency'),
        ]);
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function destroy(Accommodation $accommodation)
    {
        $accommodation->delete();
        return redirect()->route('accommodations.all');
    }
}
