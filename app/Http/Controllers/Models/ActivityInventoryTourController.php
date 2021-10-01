<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\ActivityInventoryTour;
use Illuminate\Http\Request;

class ActivityInventoryTourController extends Controller {

  public function index() {
    return view('pages.models.activity_inventory_tours.table', ['activityInventoryTours' => ActivityInventoryTour::all(),]);
  }

  public function create() {
    return view('pages.models.activity_inventory_tours.create');
  }

  public function store(Request $request) {
    $activityInventoryTour = ActivityInventoryTour::create([
      'tour_id' => $request->input('tour_id'),
      'activity_inventory_id' => $request->input('activity_inventory_id'),
      'tour_component_type' => $request->input('tour_component_type'),
      'tour_sales_price' => $request->input('tour_sales_price'),
    ]);
    return redirect()->route('activity-inventory-tours.view', ['activityInventoryTour' => $activityInventoryTour, ]);
  }

  public function view(ActivityInventoryTour $activityInventoryTour) {
    return view('pages.models.activity_inventory_tours.view', ['activityInventoryTour' => $activityInventoryTour, ]);
  }

  public function edit(ActivityInventoryTour $activityInventoryTour) {
    return view('pages.models.activity_inventory_tours.update', ['activityInventoryTour' => $activityInventoryTour, ]);
  }

  public function update(Request $request, ActivityInventoryTour $activityInventoryTour) {
    $activityInventoryTour->update([
      'tour_id' => $request->input('tour_id'),
      'activity_inventory_id' => $request->input('activity_inventory_id'),
      'tour_component_type' => $request->input('tour_component_type'),
      'tour_sales_price' => $request->input('tour_sales_price'),
    ]);
    return redirect()->route('activity-inventory-tours.view', ['activityInventoryTour' => $activityInventoryTour, ]);
  }

  public function destroy(ActivityInventoryTour $activityInventoryTour) {
    $activityInventoryTour->delete();
    return redirect()->route('activity-inventory-tours.all');
  }
}
