<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\OrderCustomerAdjustment;
use Illuminate\Http\Request;

class OrderCustomerAdjustmentController extends Controller {

  public function index() {
    return view('pages.models.order_customer_adjustments.table', ['orderCustomerAdjustments' => OrderCustomerAdjustment::all(),]);
  }

  public function create() {
    return view('pages.models.order_customer_adjustments.create');
  }

  public function store(Request $request) {
    $orderCustomerAdjustment = OrderCustomerAdjustment::create([
      'order_customer_id' => $request->input('order_customer_id'),
      'amount' => $request->input('amount'),
      'reason' => $request->input('reason'),
    ]);
    return redirect()->route('order-customer-adjustments.view', ['orderCustomerAdjustment' => $orderCustomerAdjustment, ]);
  }

  public function view(OrderCustomerAdjustment $orderCustomerAdjustment) {
    return view('pages.models.order_customer_adjustments.view', ['orderCustomerAdjustment' => $orderCustomerAdjustment, ]);
  }

  public function edit(OrderCustomerAdjustment $orderCustomerAdjustment) {
    return view('pages.models.order_customer_adjustments.update', ['orderCustomerAdjustment' => $orderCustomerAdjustment, ]);
  }

  public function update(Request $request, OrderCustomerAdjustment $orderCustomerAdjustment) {
    $orderCustomerAdjustment->update([
      'order_customer_id' => $request->input('order_customer_id'),
      'amount' => $request->input('amount'),
      'reason' => $request->input('reason'),
    ]);
    return redirect()->route('order-customer-adjustments.view', ['orderCustomerAdjustment' => $orderCustomerAdjustment, ]);
  }

  public function destroy(OrderCustomerAdjustment $orderCustomerAdjustment) {
    $orderCustomerAdjustment->delete();
    return redirect()->route('order-customer-adjustments.all');
  }
}
