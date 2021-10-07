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
        return view('pages.models.activity_inventories.create', ['activity' => $activity, ]);
    }

    public function store(Request $request, Activity $activity)
    {
        $activityInventory = ActivityInventory::make([
            'ticket_type_id' => $request->input('ticket_type_id'),
            'activity_start_date_time' => $request->input('activity_start_date_time'),
            'activity_end_date_time' => $request->input('activity_end_date_time'),
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'sales_price' => $request->input('sales_price'),
            'currency' => $request->input('currency'),
            'notes' => $request->input('notes'),
        ]);
        $activity->activityInventory()->save($activityInventory);
        return redirect()->route('activities.view', ['activity' => $activity, ]);
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
        $activityInventory->update([
            'ticket_type_id' => $request->input('ticket_type_id'),
            'activity_start_date_time' => $request->input('activity_start_date_time'),
            'activity_end_date_time' => $request->input('activity_end_date_time'),
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'sales_price' => $request->input('sales_price'),
            'currency' => $request->input('currency'),
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('activities.view', ['activity' => $activity, ]);
    }

    public function destroy(Activity $activity, ActivityInventory $activityInventory)
    {
        $activityInventory->delete();
        return redirect()->route('activities.view', ['activity' => $activity, ]);
    }
}
