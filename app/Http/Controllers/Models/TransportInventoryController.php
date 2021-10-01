<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\TransportInventory;
use Illuminate\Http\Request;

class TransportInventoryController extends Controller {

  public function index() {
    return view('pages.models.transport_inventories.table', ['transportInventories' => TransportInventory::all(),]);
  }

  public function create() {
    return view('pages.models.transport_inventories.create');
  }

  public function store(Request $request) {
    $transportInventory = TransportInventory::create([
      'transport_id' => $request->input('transport_id'),
      'travel_class_id' => $request->input('travel_class_id'),
      'departure_date_time' => $request->input('departure_date_time'),
      'departure_confirmed' => $request->input('departure_confirmed'),
      'arrival_date_time' => $request->input('arrival_date_time'),
      'arrival_confirmed' => $request->input('arrival_confirmed'),
      'fit_selectable' => $request->input('fit_selectable'),
      'stock' => $request->input('stock'),
      'purchase_price' => $request->input('purchase_price'),
      'sales_price' => $request->input('sales_price'),
      'currency' => $request->input('currency'),
      'notes' => $request->input('notes'),
    ]);
    return redirect()->route('transport-inventories.view', ['transportInventory' => $transportInventory, ]);
  }

  public function view(TransportInventory $transportInventory) {
    return view('pages.models.transport_inventories.view', ['transportInventory' => $transportInventory, ]);
  }

  public function edit(TransportInventory $transportInventory) {
    return view('pages.models.transport_inventories.update', ['transportInventory' => $transportInventory, ]);
  }

  public function update(Request $request, TransportInventory $transportInventory) {
    $transportInventory->update([
      'transport_id' => $request->input('transport_id'),
      'travel_class_id' => $request->input('travel_class_id'),
      'departure_date_time' => $request->input('departure_date_time'),
      'departure_confirmed' => $request->input('departure_confirmed'),
      'arrival_date_time' => $request->input('arrival_date_time'),
      'arrival_confirmed' => $request->input('arrival_confirmed'),
      'fit_selectable' => $request->input('fit_selectable'),
      'stock' => $request->input('stock'),
      'purchase_price' => $request->input('purchase_price'),
      'sales_price' => $request->input('sales_price'),
      'currency' => $request->input('currency'),
      'notes' => $request->input('notes'),
    ]);
    return redirect()->route('transport-inventories.view', ['transportInventory' => $transportInventory, ]);
  }

  public function destroy(TransportInventory $transportInventory) {
    $transportInventory->delete();
    return redirect()->route('transport-inventories.all');
  }
}
