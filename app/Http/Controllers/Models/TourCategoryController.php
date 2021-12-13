<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\TourCategory;
use Illuminate\Http\Request;

class TourCategoryController extends Controller
{

    public function index()
    {
        return view('pages.models.tour_categories.table', ['tourCategories' => TourCategory::all(),]);
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
        return redirect()->route('tour-categories.view', ['tourCategory' => $tourCategory,]);
    }

    public function view(TourCategory $tourCategory)
    {
        return view('pages.models.tour_categories.view', ['tourCategory' => $tourCategory,]);
    }

    public function edit(TourCategory $tourCategory)
    {
        return view('pages.models.tour_categories.update', ['tourCategory' => $tourCategory,]);
    }

    public function update(Request $request, TourCategory $tourCategory)
    {
        $request->validate(TourCategory::getValidationRules());
        $tourCategory->update([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('tour-categories.view', ['tourCategory' => $tourCategory,]);
    }

    public function destroy(TourCategory $tourCategory)
    {
        $tourCategory->delete();
        return redirect()->route('tour-categories.all');
    }
}
