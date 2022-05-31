<?php

namespace App\Http\Controllers\Api;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Flight\FlightInventoryTourUpgrade;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\OrderCustomer;
use App\Models\Transport\TransportInventoryTourUpgrade;
use App\Events\Order\Customer\Component\OrderCustomerComponentEditedEvent;
use App\Repository\ActivityComponentRepository;
use App\Repository\FlightComponentRepository;
use App\Repository\Model\Accommodation\AccommodationInventoryTourRepository;
use App\Repository\Model\Activity\ActivityInventoryTourRepository;
use App\Repository\StaticOrderRepository;
use App\Repository\TransportComponentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TourComponentController extends Controller
{
    public function getAvailableAccommodationAddons($oCustomerId) {
        $oCustomer = OrderCustomer::findOrFail($oCustomerId);
        return AccommodationInventoryTourRepository::getAvailableAddons($oCustomer->order->tour, $oCustomer);
    }

    public function getAvailableActivityAddons($oCustomerId) {
        $oCustomer = OrderCustomer::findOrFail($oCustomerId);
        return ActivityInventoryTourRepository::getAvailableAddons($oCustomer->order->tour, $oCustomer);
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
        $request->validate(['customer_id' => 'required|exists:order_customers,id', 'accommodation_id' => 'required|exists:accommodation_inventory_tours,id']);
        $orderCustomer = OrderCustomer::find($request->input('customer_id'));
        $inventoryTour = AccommodationInventoryTour::find($request->input('accommodation_id'));
        $orderInventory = $inventoryTour->repository->grantToCustomer($orderCustomer)->get();
        if (!isset($orderInventory)) return response()->json(['success' => false, 'message' => 'Customer does not have a group assigned',]);
        return $orderInventory;
    }

    public function addActivityAddon(Request $request) {
        $request->validate(['customer_id' => 'required|exists:order_customers,id', 'activity_id' => 'required|exists:activity_inventory_tours,id']);
        $oCustomerId = $request->input('customer_id');
        $activityInventoryTourId = $request->input('activity_id');
        return ActivityComponentRepository::grantAddonToCustomer($oCustomerId, $activityInventoryTourId);
    }

    public function addFlightAddon(Request $request) {
        $request->validate(['customer_id' => 'required|exists:order_customers,id', 'flight_id' => 'required|exists:flight_inventory_tours,id']);
        $oCustomerId = $request->input('customer_id');
        $flightInventoryTourId = $request->input('flight_id');
        return FlightComponentRepository::grantAddonToCustomer($oCustomerId, $flightInventoryTourId);
    }

    public function addTransportAddon(Request $request) {
        $request->validate(['customer_id' => 'required|exists:order_customers,id', 'transport_id' => 'required|exists:transport_inventory_tours,id']);
        $oCustomerId = $request->input('customer_id');
        $transportInventoryTourId = $request->input('transport_id');
        return TransportComponentRepository::grantAddonToCustomer($oCustomerId, $transportInventoryTourId);
    }

    public function addMerchandiseAddon(Request $request) {
        $request->validate(['customer_id' => 'required|exists:order_customers,id', 'merchandise_id' => 'required|exists:merchandises,id']);
        $oCustomerId = $request->input('customer_id');
        $merchandiseId = $request->input('merchandise_id');
        $oMerch = StaticOrderRepository::grantMerchandiseToCustomer($oCustomerId, $merchandiseId);
        if (!isset($oMerch)) return response()->json(['success' => false, 'message' => 'Customer already has selected merchandise']);
        return $oMerch;
    }

    public function applyAccommodationUpgrade(Request $request): JsonResponse
    {
        $orderComponent = OrderAccommodation::find($request->input('component_id'));
        if (!isset($orderComponent)) return response()->json(['success' => false, 'message' => 'Cannot find requested order component',]);
        if ($request->input('upgrade_id') == 0) {
            $parent = $orderComponent->tourComponent->parent();
            $upgrade = $parent->upgrades()->first();
        } else {
            $upgrade = AccommodationInventoryTourUpgrade::find($request->input('upgrade_id'));
        }
        if (!isset($upgrade)) return response()->json(['success' => false, 'message' => 'Cannot find requested upgrade',]);
        if (!$orderComponent->tourComponent->repository->onUpgradeTree($upgrade->repository)) return response()->json(['success' => false, 'message' => 'Requested upgrade not on inventory upgrade tree',]);
        $orderComponent->swap($request->input('upgrade_id') == 0 ? $upgrade->base : $upgrade->upgrade);
        return response()->json(['success' => true, 'message' => 'Upgrade has been applied successfully']);
    }

    public function applyActivityUpgrade(Request $request): JsonResponse
    {
        $orderComponent = OrderActivity::find($request->input('component_id'));
        if (!isset($orderComponent)) return response()->json(['success' => false, 'message' => 'Cannot find requested order component',]);
        if ($request->input('upgrade_id') == 0) {
            $parent = $orderComponent->tourComponent->parent();
            $upgrade = $parent->upgrades()->first();
        } else {
            $upgrade = ActivityInventoryTourUpgrade::find($request->input('upgrade_id'));
        }
        if (!isset($upgrade)) return response()->json(['success' => false, 'message' => 'Cannot find requested upgrade',]);
        if (!ActivityComponentRepository::isOnUpgradeTree($orderComponent->tourComponent, $upgrade)) return response()->json(['success' => false, 'message' => 'Requested upgrade not on inventory upgrade tree',]);
        $orderComponent->swap($request->input('upgrade_id') == 0 ? $upgrade->base : $upgrade->upgrade);
        event(new OrderCustomerComponentEditedEvent($orderComponent));
        return response()->json(['success' => true, 'message' => 'Upgrade has been applied successfully']);
    }

    public function applyFlightUpgrade(Request $request): JsonResponse
    {
        $orderComponent = OrderFlight::find($request->input('component_id'));
        if (!isset($orderComponent)) return response()->json(['success' => false, 'message' => 'Cannot find requested order component',]);
        if ($request->input('upgrade_id') == 0) {
            $parent = $orderComponent->tourComponent->parent();
            $upgrade = $parent->upgrades()->first();
        } else {
            $upgrade = FlightInventoryTourUpgrade::find($request->input('upgrade_id'));
        }
        if (!isset($upgrade)) return response()->json(['success' => false, 'message' => 'Cannot find requested upgrade',]);
        if (!FlightComponentRepository::isOnUpgradeTree($orderComponent->tourComponent, $upgrade)) return response()->json(['success' => false, 'message' => 'Requested upgrade not on inventory upgrade tree',]);
        $orderComponent->swap($request->input('upgrade_id') == 0 ? $upgrade->base : $upgrade->upgrade);
        event(new OrderCustomerComponentEditedEvent($orderComponent));
        return response()->json(['success' => true, 'message' => 'Upgrade has been applied successfully']);
    }

    public function applyTransportUpgrade(Request $request): JsonResponse
    {
        $orderComponent = OrderTransport::find($request->input('component_id'));
        if (!isset($orderComponent)) return response()->json(['success' => false, 'message' => 'Cannot find requested order component',]);
        if ($request->input('upgrade_id') == 0) {
            $parent = $orderComponent->tourComponent->parent();
            $upgrade = $parent->upgrades()->first();
        } else {
            $upgrade = TransportInventoryTourUpgrade::find($request->input('upgrade_id'));
        }
        if (!isset($upgrade)) return response()->json(['success' => false, 'message' => 'Cannot find requested upgrade',]);
        if (!TransportComponentRepository::isOnUpgradeTree($orderComponent->tourComponent, $upgrade)) return response()->json(['success' => false, 'message' => 'Requested upgrade not on inventory upgrade tree',]);
        $orderComponent->swap($request->input('upgrade_id') == 0 ? $upgrade->base : $upgrade->upgrade);
        event(new OrderCustomerComponentEditedEvent($orderComponent));
        return response()->json(['success' => true, 'message' => 'Upgrade has been applied successfully']);
    }
}
