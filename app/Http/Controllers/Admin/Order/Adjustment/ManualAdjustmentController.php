<?php

namespace App\Http\Controllers\Admin\Order\Adjustment;

use App\Events\Order\Adjustment\AdjustmentCreatedEvent;
use App\Events\Order\Adjustment\AdjustmentEditedEvent;
use App\Events\Order\Adjustment\AdjustmentRemovedEvent;
use App\Http\Controllers\Controller;
use App\Models\Order\Adjustment\ManualAdjustment;
use App\Models\Order\Order;
use Illuminate\Http\Request;

class ManualAdjustmentController extends Controller
{
    public function create(Order $order)
    {
        return view('pages.admin.order.adjustment.form', ['order' => $order,]);
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
        event(new AdjustmentCreatedEvent($manualAdjustment));
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function edit(Order $order, ManualAdjustment $manualAdjustment)
    {
        return view('pages.admin.order.adjustment.form', ['order' => $order, 'manualAdjustment' => $manualAdjustment,]);
    }

    public function update(Request $request, Order $order, ManualAdjustment $manualAdjustment)
    {
        $request->validate(ManualAdjustment::getValidationRules());
        $manualAdjustment->update([
            'amount' => $request->input('amount'),
            'reason' => $request->input('reason'),
            'date' => $request->input('date'),
        ]);
        event(new AdjustmentEditedEvent($manualAdjustment));
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function destroy(Order $order, ManualAdjustment $manualAdjustment)
    {
        $manualAdjustment->delete();
        event(new AdjustmentRemovedEvent($manualAdjustment));
        return redirect()->route('orders.view', ['order' => $order,]);
    }
}
