<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventory;
use App\Models\Transport\TransportInventoryTour;
use Illuminate\Http\Request;
use App\Repository\TransportRepository;

class TransportController extends ApiController
{
    /**
     * Route::get('/transports/tour/{tour}', [TransportsController::class, 'getTransportsInventoryForTour']);
     * Route::get('/transports/booking/{token}/tour/{tour}', [TransportsController::class, 'getTransportsBooking']);
     * Route::post('/transports/booking', [TransportsController::class, 'updateTransports']);
     */
    /**
     * getTransportsInventoryForTour
     *
     * @param Tour $tour
     * @return Response (transports collection)
     */
    public function getTransportsInventoryForTour(Tour $tour)
    {
        $transportsRepo = new TransportRepository();
        $transports = $transportsRepo->get($tour);

        return response()->json(['success' => true, 'transports' => $transports]);
    }

    public function getTransportsBooking($token, Tour $tour)
    {

    }
    public function updateTransports(Request $request)
    {

    }

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
