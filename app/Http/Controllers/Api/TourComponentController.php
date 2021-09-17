<?php

namespace App\Http\Controllers\Api;

use App\Models\OrdersCustomer;
use App\Repository\AccommodationComponentRepository;
use App\Repository\ActivityComponentRepository;
use App\Repository\FlightComponentRepository;
use App\Repository\TransportComponentRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TourComponentController extends Controller
{
    public function getAvailableAccommodationAddons($oCustomerId) {
        $oCustomer = OrdersCustomer::findOrFail($oCustomerId);
        return AccommodationComponentRepository::getAvailableAddons($oCustomer->order->tour->id, $oCustomerId);
    }

    public function getAvailableActivityAddons($oCustomerId) {
        $oCustomer = OrdersCustomer::findOrFail($oCustomerId);
        return ActivityComponentRepository::getAvailableAddons($oCustomer->order->tour->id, $oCustomerId);
    }

    public function getAvailableFlightAddons($oCustomerId) {
        $oCustomer = OrdersCustomer::findOrFail($oCustomerId);
        return FlightComponentRepository::getAvailableAddons($oCustomer->order->tour->id, $oCustomerId);
    }

    public function getAvailableTransportAddons($oCustomerId) {
        $oCustomer = OrdersCustomer::findOrFail($oCustomerId);
        return TransportComponentRepository::getAvailableAddons($oCustomer->order->tour->id, $oCustomerId);
    }

    public function addAccommodationAddon(Request $request) {
        $oCustomerId = $request->input('customer_id');
        $accommodationInventoryTourId = $request->input('accommodation_id');
        return AccommodationComponentRepository::grantAddonToCustomer($oCustomerId, $accommodationInventoryTourId);
    }
}
