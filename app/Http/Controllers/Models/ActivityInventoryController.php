<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityInventory;
use Illuminate\Http\Request;

class ActivityInventoryController extends Controller
{

    public function index()
    {
        return view('pages.models.activity_inventories.table', ['activityInventories' => ActivityInventory::all(),]);
    }

    public function create(Activity $activity)
    {
        return view('pages.models.activity_inventories.create', ['activity' => $activity,]);
    }

    public function store(Request $request, Activity $activity)
    {
        $request->validate(ActivityInventory::getValidationRules());
        $activityInventory = ActivityInventory::make([
            'ticket_type_id' => $request->input('ticket_type_id'),
            'starts_at' => $request->input('starts_at'),
            'ends_at' => $request->input('ends_at'),
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'sales_price' => $request->input('sales_price'),
            'notes' => $request->input('notes'),
        ]);
        $activity->activityInventory()->save($activityInventory);
        return redirect()->route('activities.view', ['activity' => $activity,]);
    }

    public function view(Activity $activity, ActivityInventory $activityInventory)
    {
        return view('pages.models.activity_inventories.view', ['activity' => $activity, 'activityInventory' => $activityInventory,]);
    }

    public function edit(Activity $activity, ActivityInventory $activityInventory)
    {
        return view('pages.models.activity_inventories.update', ['activity' => $activity, 'activityInventory' => $activityInventory,]);
    }

    public function update(Request $request, Activity $activity, ActivityInventory $activityInventory)
    {
        $request->validate(ActivityInventory::getValidationRules());
        $activityInventory->update([
            'ticket_type_id' => $request->input('ticket_type_id'),
            'starts_at' => $request->input('starts_at'),
            'ends_at' => $request->input('ends_at'),
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'sales_price' => $request->input('sales_price'),
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('activities.view', ['activity' => $activity,]);
    }

    public function destroy(Activity $activity, ActivityInventory $activityInventory)
    {
        if ($activityInventory->tourComponents()->count() > 0) {
            return back()->withErrors(trans('custom.used-in-tour', ['model' => 'Activity Inventory']));
        }
        $activityInventory->delete();
        return redirect()->route('activities.view', ['activity' => $activity,]);
    }

    public function duplicate(Activity $activity, ActivityInventory $activityInventory)
    {
        $inventory = $activityInventory->replicate();
        $inventory->save();
        return redirect()->route('activity-inventories.edit', ['activity' => $activity, 'activityInventory' => $inventory,]);
    }
}
