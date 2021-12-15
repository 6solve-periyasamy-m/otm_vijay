<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\ActivityInventoryTour;
use App\Models\Tour;
use Illuminate\Http\Request;

class ActivityInventoryTourController extends Controller
{

    public function index(Tour $tour)
    {
        return view('pages.models.activity_inventory_tours.table', ['tour' => $tour, 'activityInventoryTours' => ActivityInventoryTour::all(),]);
    }

    public function create(Tour $tour)
    {
        return view('pages.models.activity_inventory_tours.create', ['tour' => $tour,]);
    }

    public function store(Request $request, Tour $tour)
    {
        $request->validate(ActivityInventoryTour::getValidationRules());
        $activityInventoryTour = ActivityInventoryTour::make([
            'activity_inventory_id' => $request->input('activity_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'tour_sales_price' => $request->input('tour_sales_price'),
        ]);
        $tour->activityInventoryTours()->save($activityInventoryTour);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function view(Tour $tour, ActivityInventoryTour $activityInventoryTour)
    {
        return view('pages.models.activity_inventory_tours.view', ['tour' => $tour, 'activityInventoryTour' => $activityInventoryTour,]);
    }

    public function edit(Tour $tour, ActivityInventoryTour $activityInventoryTour)
    {
        return view('pages.models.activity_inventory_tours.update', ['tour' => $tour, 'activityInventoryTour' => $activityInventoryTour,]);
    }

    public function update(Request $request, Tour $tour, ActivityInventoryTour $activityInventoryTour)
    {
        $request->validate(ActivityInventoryTour::getValidationRules());
        $activityInventoryTour->update([
            'activity_inventory_id' => $request->input('activity_inventory_id'),
            'tour_component_type' => $request->input('tour_component_type'),
            'tour_sales_price' => $request->input('tour_sales_price'),
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour, ActivityInventoryTour $activityInventoryTour)
    {
        $activityInventoryTour->delete();
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }
}
