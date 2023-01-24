<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Tour\TourCategory;
use Illuminate\Http\Request;

class TourCategoryController extends Controller
{

    public function index()
    {
        return redirect()->route('attributes.edit');
    }

    public function create()
    {
        return view('pages.models.tour_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(TourCategory::getValidationRules());
        $tourCategory = TourCategory::create([
            'name' => $request->input('name'),
        ]);
        return $tourCategory->repository->getReturnURL();
    }

    public function view(TourCategory $tourCategory)
    {
        return $tourCategory->repository->getReturnURL();
    }

    public function edit(TourCategory $tourCategory)
    {
        return $tourCategory->repository->getReturnURL();
    }

    public function update(Request $request, TourCategory $tourCategory)
    {
        $request->validate(TourCategory::getValidationRules());
        $tourCategory->update([
            'name' => $request->input('name'),
        ]);
        return $tourCategory->repository->getReturnURL();
    }

    public function destroy(TourCategory $tourCategory)
    {
        $tourCategory->delete();
        return redirect()->route('attributes.edit');
    }
}
