<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use App\Models\ActivityInventory;
use App\Models\ActivityInventoryTour;
use App\Models\Tour;
use Illuminate\Http\Request;

class ActivityController extends ApiController
{
    public function addActivityInventoryToTour(Request $request, Tour $tour) {
        // TODO: Get actual enum values
        if ($request->has('type') && in_array($request->input('type'), ['Included', 'Add-on', 'Upgrade'])) {
            if ($request->has('ids')) {
                foreach ($request->input('ids') as $id) {
                    $inventory = ActivityInventory::findOrFail($id);
                    $inventoryTour = ActivityInventoryTour::make([
                        'activity_inventory_id' => $id,
                        'tour_component_type' => $request->input('type'),
                        'tour_sales_price' => $inventory->sales_price,
                    ]);
                    $tour->activityInventoryTours()->save($inventoryTour);
                }
            }
            return response('Any listed components have been successfully added', 200);
        }
        abort(400, 'Invalid component type has been provided');
        return null;
    }
}
