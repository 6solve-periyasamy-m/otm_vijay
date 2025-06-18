<?php

namespace App\Http\Controllers\Admin\Transport;

use App\Http\Controllers\Controller;
use App\Models\Transport\TransportOccupancy;
use Illuminate\Http\Request;

class TransportOccupancyController extends Controller
{
    public function create()
    {
        return view('pages.admin.transport.occupancy.form');
    }

    public function store(Request $request)
    {
        $request->validate(TransportOccupancy::getValidationRules());

        TransportOccupancy::create([
            'name' => $request->input('name'),
            'maximum_occupancy' => abs($request->input('maximum_occupancy')),
        ]);

        return view('pages.close');
    }

    public function edit(TransportOccupancy $occupancy)
    {
        return view('pages.admin.transport.occupancy.form', [
            'transportOccupancy' => $occupancy,
        ]);
    }

    public function update(Request $request, TransportOccupancy $occupancy)
    {
        $request->validate(TransportOccupancy::getValidationRules($occupancy->id));        
        $occupancy->update([
            'name' => $request->input('name'),
            'maximum_occupancy' => $request->input('maximum_occupancy'),
        ]);
        return view('pages.close');
    }

    public function destroy(TransportOccupancy $occupancy)
    {
        $repo = $occupancy->repository;
        $repo->delete();

        return $repo->getReturnURL();
    }
}