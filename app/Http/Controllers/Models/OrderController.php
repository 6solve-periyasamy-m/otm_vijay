<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller {

  public function index() {
    return view('pages.models.orders.table', ['orders' => Order::all(),]);
  }

  public function create() {
    return view('pages.models.orders.create');
  }

  public function store(Request $request) {
    $order = Order::create([
      'quote_id' => $request->input('quote_id'),
      'tour_id' => $request->input('tour_id'),
      'lead_booker_id' => $request->input('lead_booker_id'),
      'token' => $request->input('token'),
      'booking_reference' => $request->input('booking_reference'),
      'ordered_on' => $request->input('ordered_on'),
      'internal_notes' => $request->input('internal_notes'),
      'external_notes' => $request->input('external_notes'),
    ]);
    return redirect()->route('orders.view', ['order' => $order, ]);
  }

  public function view(Order $order) {
    return view('pages.models.orders.view', ['order' => $order, ]);
  }

  public function edit(Order $order) {
    return view('pages.models.orders.update', ['order' => $order, ]);
  }

  public function update(Request $request, Order $order) {
    $order->update([
      'quote_id' => $request->input('quote_id'),
      'tour_id' => $request->input('tour_id'),
      'lead_booker_id' => $request->input('lead_booker_id'),
      'token' => $request->input('token'),
      'booking_reference' => $request->input('booking_reference'),
      'ordered_on' => $request->input('ordered_on'),
      'internal_notes' => $request->input('internal_notes'),
      'external_notes' => $request->input('external_notes'),
    ]);
    return redirect()->route('orders.view', ['order' => $order, ]);
  }

  public function destroy(Order $order) {
    $order->delete();
    return redirect()->route('orders.all');
  }
}
