<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\ManualAdjustment;
use App\Models\Order;
use Illuminate\Http\Request;

class ManualAdjustmentController extends Controller
{

    public function index(Order $order)
    {
        return view('pages.models.manual_adjustments.table', ['order' => $order, 'manualAdjustments' => ManualAdjustment::all(),]);
    }

    public function create(Order $order)
    {
        return view('pages.models.manual_adjustments.create', ['order' => $order,]);
    }

    public function store(Request $request, Order $order)
    {
        $request->validate(ManualAdjustment::getValidationRules());
        $manualAdjustment = ManualAdjustment::make([
            'amount' => $request->input('amount'),
            'reason' => $request->input('reason'),
            'date' => $request->input('date'),
        ]);
        $order->adjustments()->save($manualAdjustment);
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function view(Order $order, ManualAdjustment $manualAdjustment)
    {
        return view('pages.models.manual_adjustments.view', ['order' => $order, 'manualAdjustment' => $manualAdjustment,]);
    }

    public function edit(Order $order, ManualAdjustment $manualAdjustment)
    {
        return view('pages.models.manual_adjustments.update', ['order' => $order, 'manualAdjustment' => $manualAdjustment,]);
    }

    public function update(Request $request, Order $order, ManualAdjustment $manualAdjustment)
    {
        $request->validate(ManualAdjustment::getValidationRules());
        $manualAdjustment->update([
            'amount' => $request->input('amount'),
            'reason' => $request->input('reason'),
            'date' => $request->input('date'),

        ]);
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function destroy(Order $order, ManualAdjustment $manualAdjustment)
    {
        $manualAdjustment->delete();
        return redirect()->route('orders.view', ['order' => $order,]);
    }
}
