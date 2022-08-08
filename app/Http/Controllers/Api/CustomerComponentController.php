<?php

namespace App\Http\Controllers\Api;

use App\Http\Gateways\StripeGateway;
use App\Http\Requests\Api\Customer\CustomerUpgradeRequest;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Flight\FlightInventoryTourUpgrade;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderTransport;
use App\Models\Transport\TransportInventoryTourUpgrade;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class CustomerComponentController extends Controller
{
    public function apply(CustomerUpgradeRequest $request)
    {
        if (flag('payment.required', true)) abort(404);

        $component = $request->getOrderComponent();
        $upgrade = $request->getUpgradeComponent();

        $validation = $this->verify($component, $upgrade);
        if (isset($validation)) {
            return $validation;
        }

        $component->get()->swap($upgrade->get()->upgrade);

        return response()->json(['success' => true, 'message' => 'Upgrade has been applied successfully']);
    }

    public function purchase(CustomerUpgradeRequest $request)
    {
        $component = $request->getOrderComponent();
        $upgrade = $request->getUpgradeComponent();

        $validation = $this->verify($component, $upgrade);

        if (isset($validation)) {
            return $validation;
        }

        return $this->generateResponse($request->model, $component->get(), $upgrade->get());
    }

    private function verify(OrderComponentRepository $component, ComponentUpgradeRepository $upgrade)
    {
        if (!isset($component)) {
            return response()->json(['success' => false, 'message' => 'Cannot find requested order component',]);
        }

        if ($component->get()->cancelled) {
            return response()->json(['success' => false, 'message' => 'That order is cancelled',]);
        }

        if (!isset($upgrade) || !$upgrade->get()->upgrade->is_bookable) {
            return response()->json(['success' => false, 'message' => 'Cannot find requested upgrade',]);
        }
        if ($upgrade->get()->upgrade->tour_sales_price < $component->getCost() && $component->getTourComponentType() != 'Included') {
            return response()->json(['success' => false, 'message' => 'Please contact us if you wish to downgrade',]);
        }

        if (!$upgrade->get()->upgrade->repository->hasEnoughStock(1)) {
            return response()->json(['success' => false, 'message' => 'This upgrade is currently out of stock',]);
        }

        if (!$component->getTourComponent()->onUpgradeTree($upgrade)) {
            return response()->json(['success' => false, 'message' => 'Requested upgrade not on inventory upgrade tree',]);
        }

        return null;
    }

    /**
     * @param string $component
     * @param OrderAccommodation|OrderActivity|OrderFlight|OrderTransport $orderComponent
     * @param AccommodationInventoryTourUpgrade|ActivityInventoryTourUpgrade|FlightInventoryTourUpgrade|TransportInventoryTourUpgrade $upgrade
     * @return JsonResponse
     */
    private function generateResponse(string $component,
          OrderTransport|OrderFlight|OrderAccommodation|OrderActivity $orderComponent,
          FlightInventoryTourUpgrade|AccommodationInventoryTourUpgrade|TransportInventoryTourUpgrade|ActivityInventoryTourUpgrade $upgrade): JsonResponse
    {
        $orderCustomer = $orderComponent->group?->orderCustomers()?->first() ?? $orderComponent->orderCustomer;
        $data = [
            'upgrades' => [[
                'customer' => $orderCustomer?->customer->id,
                'component' => $component,
                'from' => $orderComponent->tourComponent->id,
                'to' => $upgrade->upgrade->id,
            ],],
        ];
        $redirect = setting('purchase.upgrade.success.redirect', route('customer.extras', ['reference' => $orderCustomer->order->booking_reference, 'customer' => $orderCustomer->customer,]));

        $gateway = StripeGateway::checkout([['name' => $upgrade->description, 'cost' => $upgrade->upgrade->tour_sales_price, 'quantity' => 1],],
            $orderCustomer->order->booking_reference, 'Installment', $orderCustomer->customer->id, $redirect, $data);
        return response()->json(['success' => true, 'location' => $gateway->headers->get('Location')]);
    }
}
