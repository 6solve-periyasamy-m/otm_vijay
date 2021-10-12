<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{

    public function index()
    {
        return view('pages.models.regions.table', ['regions' => Region::all(),]);
    }

    public function create()
    {
        return view('pages.models.regions.create');
    }

    public function store(Request $request)
    {
        $region = Region::create([
            'country_id' => $request->input('country_id'),
            'name' => $request->input('name'),
        ]);
        return redirect()->route('regions.view', ['region' => $region,]);
    }

    public function view(Region $region)
    {
        return view('pages.models.regions.view', ['region' => $region,]);
    }

    public function edit(Region $region)
    {
        return view('pages.models.regions.update', ['region' => $region,]);
    }

    public function update(Request $request, Region $region)
    {
        $region->update([
            'country_id' => $request->input('country_id'),
            'name' => $request->input('name'),
        ]);
        return redirect()->route('regions.view', ['region' => $region,]);
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->route('regions.all');
    }
}
