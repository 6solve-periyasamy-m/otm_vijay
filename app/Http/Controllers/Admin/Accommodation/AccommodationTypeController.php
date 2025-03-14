<?php

namespace App\Http\Controllers\Admin\Accommodation;

use App\Http\Controllers\Controller;
use App\Models\Accommodation\AccommodationType;
use Illuminate\Http\Request;

class AccommodationTypeController extends Controller
{
    public function create()
    {
        return view('pages.admin.accommodation.type.form');
    }

    public function store(Request $request)
    {
        $request->validate(AccommodationType::getValidationRules());
        $accommodationType = AccommodationType::create([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function edit(AccommodationType $accommodationType)
    {
        return view('pages.admin.accommodation.type.form', ['accommodationType' => $accommodationType,]);
    }

    public function update(Request $request, AccommodationType $accommodationType)
    {
        $request->validate(AccommodationType::getValidationRules($accommodationType->id));
        $accommodationType->update([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function destroy(AccommodationType $accommodationType)
    {
        $repo = $accommodationType->repository;
        $repo->delete();
        return $repo->getReturnURL();
    }
}
