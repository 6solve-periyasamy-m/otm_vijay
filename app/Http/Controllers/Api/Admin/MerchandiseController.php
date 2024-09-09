<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use App\Models\Merchandise\Merchandise;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Tour\Tour;
use Illuminate\Http\Request;

class MerchandiseController extends ApiController
{

    public function addMerchandiseToTour(Request $request, Tour $tour) {
        // TODO: Get actual enum values
        if ($request->has('type') && in_array($request->input('type'), ['Included', 'Add-on',])) {
            if ($request->has('ids')) {
                foreach ($request->input('ids') as $id) {
                    $merchandise = Merchandise::findOrFail($id);
                    $merchandise->repository->addAllInventoryToTour($tour, $request->input('type'));
                }
            }
            return response('Any listed components have been successfully added', 200);
        }
        abort(400, 'Invalid component type has been provided');
        return null;
    }

    public function fulfil(Request $request)
    {
        if ($request->has('ids')) {
            foreach ($request->input('ids') as $id) {
                $orderMerchandise = OrderMerchandise::find($id);
                $orderMerchandise->repository->update(['fulfilled' => true,]);
            }
        }
        return response('Any listed order merchandise have been fulfilled', 200);
    }
}
