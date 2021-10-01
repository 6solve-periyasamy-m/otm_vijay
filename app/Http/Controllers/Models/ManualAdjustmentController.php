<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\ManualAdjustment;
use Illuminate\Http\Request;

class ManualAdjustmentController extends Controller {

  public function index() {
    return view('pages.models.manual_adjustments.table', ['manualAdjustments' => ManualAdjustment::all(),]);
  }

  public function create() {
    return view('pages.models.manual_adjustments.create');
  }

  public function store(Request $request) {
    $manualAdjustment = ManualAdjustment::create([
      'order_id' => $request->input('order_id'),
      'amount' => $request->input('amount'),
      'reason' => $request->input('reason'),
    ]);
    return redirect()->route('manual-adjustments.view', ['manualAdjustment' => $manualAdjustment, ]);
  }

  public function view(ManualAdjustment $manualAdjustment) {
    return view('pages.models.manual_adjustments.view', ['manualAdjustment' => $manualAdjustment, ]);
  }

  public function edit(ManualAdjustment $manualAdjustment) {
    return view('pages.models.manual_adjustments.update', ['manualAdjustment' => $manualAdjustment, ]);
  }

  public function update(Request $request, ManualAdjustment $manualAdjustment) {
    $manualAdjustment->update([
      'order_id' => $request->input('order_id'),
      'amount' => $request->input('amount'),
      'reason' => $request->input('reason'),
    ]);
    return redirect()->route('manual-adjustments.view', ['manualAdjustment' => $manualAdjustment, ]);
  }

  public function destroy(ManualAdjustment $manualAdjustment) {
    $manualAdjustment->delete();
    return redirect()->route('manual-adjustments.all');
  }
}
