<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\AccommodationInventoryTour;
use Illuminate\Http\Request;

class AccommodationInventoryTourController extends Controller {

  public function index() {
    return view('pages.models.accommodation_inventory_tours.table', ['accommodationInventoryTours' => AccommodationInventoryTour::all(),]);
  }

  public function create() {
    return view('pages.models.accommodation_inventory_tours.create');
  }

  public function store(Request $request) {
    $accommodationInventoryTour = AccommodationInventoryTour::create([
      'tour_id' => $request->input('tour_id'),
      'accommodation_inventory_id' => $request->input('accommodation_inventory_id'),
      'tour_component_type' => $request->input('tour_component_type'),
      'tour_sales_price' => $request->input('tour_sales_price'),
    ]);
    return redirect()->route('accommodation_inventory_tours.view', ['accommodationInventoryTour' => $accommodationInventoryTour, ]);
  }

  public function view(AccommodationInventoryTour $accommodationInventoryTour) {
    return view('pages.models.accommodation_inventory_tours.view', ['accommodationInventoryTour' => $accommodationInventoryTour, ]);
  }

  public function edit(AccommodationInventoryTour $accommodationInventoryTour) {
    return view('pages.models.accommodation_inventory_tours.update', ['accommodationInventoryTour' => $accommodationInventoryTour, ]);
  }

  public function update(Request $request, AccommodationInventoryTour $accommodationInventoryTour) {
    $accommodationInventoryTour->update([
      'tour_id' => $request->input('tour_id'),
      'accommodation_inventory_id' => $request->input('accommodation_inventory_id'),
      'tour_component_type' => $request->input('tour_component_type'),
      'tour_sales_price' => $request->input('tour_sales_price'),
    ]);
    return redirect()->route('accommodation_inventory_tours.view', ['accommodationInventoryTour' => $accommodationInventoryTour, ]);
  }

  public function destroy(AccommodationInventoryTour $accommodationInventoryTour) {
    $accommodationInventoryTour->delete();
    return redirect()->route('accommodation_inventory_tours.all');
  }
}
