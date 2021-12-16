<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderCustomer;
use App\Repository\AccommodationComponentRepository;
use App\Repository\ActivityComponentRepository;
use App\Repository\FlightComponentRepository;
use App\Repository\OrderRepository;
use App\Repository\TransportComponentRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TourComponentController extends Controller
{
    public function getAvailableAccommodationAddons($oCustomerId) {
        $oCustomer = OrderCustomer::findOrFail($oCustomerId);
        return AccommodationComponentRepository::getAvailableAddons($oCustomer->order->tour->id, $oCustomerId);
    }

    public function getAvailableActivityAddons($oCustomerId) {
        $oCustomer = OrderCustomer::findOrFail($oCustomerId);
        return ActivityComponentRepository::getAvailableAddons($oCustomer->order->tour->id, $oCustomerId);
    }

    public function getAvailableFlightAddons($oCustomerId) {
        $oCustomer = OrderCustomer::findOrFail($oCustomerId);
        return FlightComponentRepository::getAvailableAddons($oCustomer->order->tour->id, $oCustomerId);
    }

    public function getAvailableTransportAddons($oCustomerId) {
        $oCustomer = OrderCustomer::findOrFail($oCustomerId);
        return TransportComponentRepository::getAvailableAddons($oCustomer->order->tour->id, $oCustomerId);
    }

    public function addAccommodationAddon(Request $request) {
        $request->validate(['customer_id' => 'required|exists:order_customers,id', 'merchandise_id' => 'required|exists:accommodations,id']);
        $oCustomerId = $request->input('customer_id');
        $accommodationInventoryTourId = $request->input('accommodation_id');
        return AccommodationComponentRepository::grantAddonToCustomer($oCustomerId, $accommodationInventoryTourId);
    }

    public function addActivityAddon(Request $request) {
        $request->validate(['customer_id' => 'required|exists:order_customers,id', 'merchandise_id' => 'required|exists:activities,id']);
        $oCustomerId = $request->input('customer_id');
        $activityInventoryTourId = $request->input('activity_id');
        return ActivityComponentRepository::grantAddonToCustomer($oCustomerId, $activityInventoryTourId);
    }

    public function addFlightAddon(Request $request) {
        $request->validate(['customer_id' => 'required|exists:order_customers,id', 'merchandise_id' => 'required|exists:flights,id']);
        $oCustomerId = $request->input('customer_id');
        $flightInventoryTourId = $request->input('flight_id');
        return FlightComponentRepository::grantAddonToCustomer($oCustomerId, $flightInventoryTourId);
    }

    public function addTransportAddon(Request $request) {
        $request->validate(['customer_id' => 'required|exists:order_customers,id', 'merchandise_id' => 'required|exists:transports,id']);
        $oCustomerId = $request->input('customer_id');
        $transportInventoryTourId = $request->input('transport_id');
        return TransportComponentRepository::grantAddonToCustomer($oCustomerId, $transportInventoryTourId);
    }

    public function addMerchandiseAddon(Request $request) {
        $request->validate(['customer_id' => 'required|exists:order_customers,id', 'merchandise_id' => 'required|exists:merchandises,id']);
        $oCustomerId = $request->input('customer_id');
        $merchandiseId = $request->input('merchandise_id');
        return OrderRepository::grantMerchandiseToCustomer($oCustomerId, $merchandiseId);
    }
}
