<?php

namespace App\Http\Controllers\Api;

use App\Models\OrdersCustomer;
use App\Repository\AccommodationComponentRepository;
use Illuminate\Routing\Controller;

class TourComponentController extends Controller
{
    public function getAvailableAccommodationAddons($oCustomerId) {
        $oCustomer = OrdersCustomer::findOrFail($oCustomerId);
        return AccommodationComponentRepository::getAvailableAddons($oCustomer->order->tour->id, $oCustomerId);
    }
}
