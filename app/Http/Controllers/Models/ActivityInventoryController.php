<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\ActivityInventory;
use Illuminate\Http\Request;

class ActivityInventoryController extends Controller {

  public function index() {
    return view('pages.models.activity_inventories.table', ['activityInventories' => ActivityInventory::all(),]);
  }

  public function create() {
    return view('pages.models.activity_inventories.create');
  }

  public function store(Request $request) {
    $activityInventory = ActivityInventory::create([
      'activity_id' => $request->input('activity_id'),
      'ticket_type_id' => $request->input('ticket_type_id'),
      'activity_start_date_time' => $request->input('activity_start_date_time'),
      'activity_end_start_date_time' => $request->input('activity_end_start_date_time'),
      'fit_selectable' => $request->input('fit_selectable'),
      'stock' => $request->input('stock'),
      'purchase_price' => $request->input('purchase_price'),
      'sales_price' => $request->input('sales_price'),
      'currency' => $request->input('currency'),
      'notes' => $request->input('notes'),
    ]);
    return redirect()->route('activity_inventories.view', ['activityInventory' => $activityInventory, ]);
  }

  public function view(ActivityInventory $activityInventory) {
    return view('pages.models.activity_inventories.view', ['activityInventory' => $activityInventory, ]);
  }

  public function edit(ActivityInventory $activityInventory) {
    return view('pages.models.activity_inventories.update', ['activityInventory' => $activityInventory, ]);
  }

  public function update(Request $request, ActivityInventory $activityInventory) {
    $activityInventory->update([
      'activity_id' => $request->input('activity_id'),
      'ticket_type_id' => $request->input('ticket_type_id'),
      'activity_start_date_time' => $request->input('activity_start_date_time'),
      'activity_end_start_date_time' => $request->input('activity_end_start_date_time'),
      'fit_selectable' => $request->input('fit_selectable'),
      'stock' => $request->input('stock'),
      'purchase_price' => $request->input('purchase_price'),
      'sales_price' => $request->input('sales_price'),
      'currency' => $request->input('currency'),
      'notes' => $request->input('notes'),
    ]);
    return redirect()->route('activity_inventories.view', ['activityInventory' => $activityInventory, ]);
  }

  public function destroy(ActivityInventory $activityInventory) {
    $activityInventory->delete();
    return redirect()->route('activity_inventories.all');
  }
}
