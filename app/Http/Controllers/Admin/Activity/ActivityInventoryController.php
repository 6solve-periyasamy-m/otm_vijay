<?php

namespace App\Http\Controllers\Admin\Activity;

use App\Exceptions\CannotDeleteException;
use App\Http\Controllers\Controller;
use App\Models\Activity\Activity;
use App\Models\Activity\ActivityInventory;
use App\Repository\Reporting\Manifest\ActivityManifestRepository;
use Illuminate\Http\Request;

class ActivityInventoryController extends Controller
{
    public function create(Activity $activity)
    {
        return view('pages.admin.activity.inventory.form', ['activity' => $activity,]);
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
            'purchase_price' => $request->input('purchase_price') ?? 0,
            'sales_price' => $request->input('sales_price') ?? 0,
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
            'description' => $request->input('description'),
        ]);
        $activity->activityInventory()->save($activityInventory);
        return redirect()->route('activities.view', ['activity' => $activity,]);
    }

    public function manifest(Activity $activity, ActivityInventory $inventory)
    {
        return ActivityManifestRepository::viewReport($inventory->repository, 'activity-inventories.manifest.export', ['activity' => $activity, 'inventory' => $inventory]);
    }

    public function export(Activity $activity, ActivityInventory $inventory, string $extension = 'xlsx')
    {
        return ActivityManifestRepository::exportReport($inventory->repository, $extension);
    }

    public function edit(Activity $activity, ActivityInventory $inventory)
    {
        return view('pages.admin.activity.inventory.form', ['activity' => $activity, 'inventory' => $inventory,]);
    }

    public function update(Request $request, Activity $activity, ActivityInventory $inventory)
    {
        $request->validate(ActivityInventory::getValidationRules());
        $inventory->update([
            'ticket_type_id' => $request->input('ticket_type_id'),
            'starts_at' => $request->input('starts_at'),
            'ends_at' => $request->input('ends_at'),
            'fit_selectable' => $request->input('fit_selectable') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price') ?? 0,
            'sales_price' => $request->input('sales_price') ?? 0,
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
            'description' => $request->input('description'),
        ]);
        return redirect()->route('activities.view', ['activity' => $activity,]);
    }

    public function destroy(Activity $activity, ActivityInventory $inventory)
    {
        try {
            $inventory->repository->delete();
        } catch (CannotDeleteException $e) {
            return back()->withErrors($e->getMessage());
        }
        return redirect()->route('activities.view', ['activity' => $activity,]);
    }

    public function duplicate(Activity $activity, ActivityInventory $inventory)
    {
        $cloned = $inventory->replicate();
        $cloned->save();
        return redirect()->route('activity-inventories.edit', ['activity' => $activity, 'inventory' => $cloned,]);
    }
}
