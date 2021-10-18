<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use App\Models\Tour;
use App\Models\TransportInventory;
use App\Models\TransportInventoryTour;
use Illuminate\Http\Request;

class TransportController extends ApiController
{
    public function addTransportInventoryToTour(Request $request, Tour $tour) {
        // TODO: Get actual enum values
        if ($request->has('type') && in_array($request->input('type'), ['Included', 'Add-on', 'Upgrade'])) {
            if ($request->has('ids')) {
                foreach ($request->input('ids') as $id) {
                    $inventory = TransportInventory::findOrFail($id);
                    $inventoryTour = TransportInventoryTour::make([
                        'transport_inventory_id' => $id,
                        'tour_component_type' => $request->input('type'),
                        'tour_sales_price' => $inventory->sales_price,
                    ]);
                    $tour->transportInventoryTours()->save($inventoryTour);
                }
            }
            return response('Any listed components have been successfully added', 200);
        }
        abort(400, 'Invalid component type has been provided');
        return null;
    }
}
