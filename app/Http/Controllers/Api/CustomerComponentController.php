<?php

namespace App\Http\Controllers\Api;

use App\Http\Gateways\StripeGateway;
use App\Models\AccommodationInventoryTourUpgrade;
use App\Models\ActivityInventoryTourUpgrade;
use App\Models\FlightInventoryTourUpgrade;
use App\Models\OrderAccommodation;
use App\Models\OrderActivity;
use App\Models\OrderFlight;
use App\Models\OrderTransport;
use App\Models\TransportInventoryTourUpgrade;
use App\Repository\AccommodationComponentRepository;
use App\Repository\ActivityComponentRepository;
use App\Repository\FlightComponentRepository;
use App\Repository\SettingsRepository;
use App\Repository\TransportComponentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CustomerComponentController extends Controller
{
    // TODO: Maybe some repo/manager to fetch all related classes for a component type? Idea for the rewrite
    public function applyAccommodationUpgrade(Request $request): JsonResponse
    {
        return $this->applyUpgrade(OrderAccommodation::class, AccommodationInventoryTourUpgrade::class,
            AccommodationComponentRepository::class, $request->input('component_id'), $request->input('upgrade_id'));
    }

    public function applyActivityUpgrade(Request $request): JsonResponse
    {
        return $this->applyUpgrade(OrderActivity::class, ActivityInventoryTourUpgrade::class,
            ActivityComponentRepository::class, $request->input('component_id'), $request->input('upgrade_id'));
    }

    public function applyFlightUpgrade(Request $request): JsonResponse
    {
        return $this->applyUpgrade(OrderFlight::class, FlightInventoryTourUpgrade::class,
            FlightComponentRepository::class, $request->input('component_id'), $request->input('upgrade_id'));
    }

    public function applyTransportUpgrade(Request $request): JsonResponse
    {
        return $this->applyUpgrade(OrderTransport::class, TransportInventoryTourUpgrade::class,
            TransportComponentRepository::class, $request->input('component_id'), $request->input('upgrade_id'));
    }

    public function purchaseAccommodationUpgrade(Request $request): JsonResponse
    {
        $orderComponent = OrderAccommodation::find($request->input('component_id'));

        if (!isset($orderComponent))
            return response()->json(['success' => false, 'message' => 'Cannot find requested order component',]);

        $upgrade = AccommodationInventoryTourUpgrade::find($request->input('upgrade_id'));

        if (!isset($upgrade))
            return response()->json(['success' => false, 'message' => 'Cannot find requested upgrade',]);
        if ($upgrade->upgrade->tour_sales_price >= $orderComponent->cost && $orderComponent->tour_component_type != 'Included')
            return response()->json(['success' => false, 'message' => 'Please contact us if you wish to downgrade',]);
        if (!AccommodationComponentRepository::isOnUpgradeTree($orderComponent->tourComponent, $upgrade)) return response()->json(['success' => false, 'message' => 'Requested upgrade not on inventory upgrade tree',]);
        $data = null;
        if (SettingsRepository::getOrDefault('payment.require', false)) {
            $data = [
                'upgrades' => [[
                    'customer' => $orderComponent->group->id,
                    'component' => 'accommodation',
                    'from' => $orderComponent->tourComponent->id,
                    'to' => $upgrade->upgrade->id,
                ],],
            ];
        } else {
            $orderComponent->swap($upgrade->upgrade);
        }
        $gateway = StripeGateway::checkout([['name' => $upgrade->description, 'cost' => $upgrade->upgrade->tour_sales_price, 'quantity' => 1],],
            $orderComponent->orderCustomer->order, 'Installment', $orderComponent->orderCustomer->customer->id, $data);
        return response()->json(['success' => true, 'location' => $gateway->headers->get('Location')]);
    }

    public function purchaseActivityUpgrade(Request $request): JsonResponse
    {
        return $this->purchaseUpgrade(OrderActivity::class, ActivityInventoryTourUpgrade::class,
            ActivityComponentRepository::class, $request->input('component_id'), $request->input('upgrade_id'), 'activity');
    }

    public function purchaseFlightUpgrade(Request $request): JsonResponse
    {
        return $this->purchaseUpgrade(OrderFlight::class, FlightInventoryTourUpgrade::class,
            FlightComponentRepository::class, $request->input('component_id'), $request->input('upgrade_id'), 'flight');
    }

    public function purchaseTransportUpgrade(Request $request): JsonResponse
    {
        return $this->purchaseUpgrade(OrderTransport::class, TransportInventoryTourUpgrade::class,
            TransportComponentRepository::class, $request->input('component_id'), $request->input('upgrade_id'), 'transport');
    }
    
    private function applyUpgrade($parentClass, $upgradeClass, $repository, $componentId, $upgradeId): JsonResponse
    {
        if (!(SettingsRepository::getBoolean('payment.required', true))) abort(404);

        $orderComponent = app($parentClass)->find($componentId);

        if (!isset($orderComponent))
            return response()->json(['success' => false, 'message' => 'Cannot find requested order component',]);

        $upgrade = app($upgradeClass)->find($upgradeId);

        if (!isset($upgrade))
            return response()->json(['success' => false, 'message' => 'Cannot find requested upgrade',]);

        if ($upgrade->upgrade->tour_sales_price < $orderComponent->cost
            && $orderComponent->tour_component_type != 'Included')
            return response()->json(['success' => false, 'message' => 'Please contact us if you wish to downgrade',]);

        if ($upgrade->upgrade->available_stock <= 0)
            return response()->json(['success' => false, 'message' => 'This upgrade is currently out of stock',]);

        if (!app($repository)->isOnUpgradeTree($orderComponent->tourComponent, $upgrade))
            return response()->json(['success' => false, 'message' => 'Requested upgrade not on inventory upgrade tree',]);

        $orderComponent->swap($upgrade->upgrade);
        return response()->json(['success' => true, 'message' => 'Upgrade has been applied successfully']);
    }

    private function purchaseUpgrade($parentClass, $upgradeClass, $repository, $componentId, $upgradeId, $type): JsonResponse
    {
        $orderComponent = app($parentClass)->find($componentId);

        if (!isset($orderComponent))
            return response()->json(['success' => false, 'message' => 'Cannot find requested order component',]);

        $upgrade = app($upgradeClass)->find($upgradeId);

        if (!isset($upgrade))
            return response()->json(['success' => false, 'message' => 'Cannot find requested upgrade',]);

        if ($upgrade->upgrade->tour_sales_price < $orderComponent->cost
            && $orderComponent->tour_component_type != 'Included')
            return response()->json(['success' => false, 'message' => 'Please contact us if you wish to downgrade',]);

        if ($upgrade->upgrade->available_stock <= 0)
            return response()->json(['success' => false, 'message' => 'This upgrade is currently out of stock',]);

        if (!app($repository)->isOnUpgradeTree($orderComponent->tourComponent, $upgrade))
            return response()->json(['success' => false, 'message' => 'Requested upgrade not on inventory upgrade tree',]);

        return $this->generateResponse($type, $orderComponent, $upgrade);
    }

    private function generateResponse(string $component, $orderComponent, $upgrade): JsonResponse
    {
        $data = [
            'upgrades' => [[
                'customer' => $orderComponent->orderCustomer->customer->id,
                'component' => $component,
                'from' => $orderComponent->tourComponent->id,
                'to' => $upgrade->upgrade->id,
            ],],
        ];
        $gateway = StripeGateway::checkout([['name' => $upgrade->description, 'cost' => $upgrade->upgrade->tour_sales_price, 'quantity' => 1],],
            $orderComponent->orderCustomer->order, 'Installment', $orderComponent->orderCustomer->customer->id, $data);
        return response()->json(['success' => true, 'location' => $gateway->headers->get('Location')]);
    }
}
