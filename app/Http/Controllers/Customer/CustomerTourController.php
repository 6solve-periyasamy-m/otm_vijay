<?php

namespace App\Http\Controllers\Customer;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Http\Controllers\Controller;
use App\Http\Gateways\StripeGateway;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Customer\Customer;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Tour\Merchandise;
use App\Models\Order\Order;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\OrderCustomer;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderTransport;
use App\Models\Transport\TransportInventoryTour;
use App\Repository\CustomerAuthenticationRepository;
use App\Repository\CustomerDashboardRepository;
use App\Repository\StaticOrderRepository;
use App\Repository\SettingsRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CustomerTourController extends Controller
{
    public function showItinerary(?string $reference = null, ?Customer $customer = null)
    {
        $customer = $customer ?? CustomerAuthenticationRepository::getCustomer();
        if (isset($reference)) {
            $order = StaticOrderRepository::getOrderFromBookingReference($reference);
        } else {
            $order = $customer->orders()->orderByDesc('ordered_on')->first();
        }
        if (CustomerAuthenticationRepository::getCustomer()->id != $customer->id) {
            if ($order->leadBooker->customer_id != CustomerAuthenticationRepository::getCustomer()->id) abort(404);
            if (isset($customer->email_address) && isset($customer->password)) abort(404);
        }
        if (!isset($order)) abort(404);
        $oCustomer = null;
        foreach ($order->orderCustomers as $orderCustomer) {
            if ($orderCustomer->customer_id == $customer->id) {
                $oCustomer = $orderCustomer;
                break;
            }
        }
        if (!isset($oCustomer)) abort(404);
        return view('pages.customer.itinerary',
            ['itinerary' => CustomerDashboardRepository::generateItinerary($oCustomer), 'orderCustomer' => $oCustomer, 'order' => $order, 'orders' => CustomerAuthenticationRepository::getCustomer()->orders, 'editable' => self::getOrderCustomers($order, CustomerAuthenticationRepository::getCustomer()),]);
    }

    public function showExtras(?string $reference = null, ?Customer $customer = null)
    {
        $customer = $customer ?? CustomerAuthenticationRepository::getCustomer();
        if (isset($reference)) {
            $order = StaticOrderRepository::getOrderFromBookingReference($reference);
        } else {
            foreach ($customer->orders()->orderBy('ordered_on', 'desc')->get() as $o) {
                if (!$o->cancelled) {
                    $order = $o;
                    break;
                }
            }
        }
        if (!isset($order) || $order->cancelled) abort(404);
        if (CustomerAuthenticationRepository::getCustomer()->id != $customer->id) {
            if ($order->leadBooker->customer_id != CustomerAuthenticationRepository::getCustomer()->id) abort(404);
            if (isset($customer->email_address) && isset($customer->password)) abort(404);
        }
        $oCustomer = null;
        foreach ($order->orderCustomers as $orderCustomer) {
            if ($orderCustomer->customer_id == $customer->id) {
                $oCustomer = $orderCustomer;
                break;
            }
        }

        if (!isset($oCustomer)) abort(404);

        $accommodation = $oCustomer->orderAccommodation()->getIterator();
        $accommodation->uasort([OrderAccommodation::class, 'compare']);
        $activities = $oCustomer->orderActivities->getIterator();
        $activities->uasort([OrderActivity::class, 'compare']);
        $flights = $oCustomer->orderFlights->getIterator();
        $flights->uasort([OrderFlight::class, 'compare']);
        $transport = $oCustomer->orderTransports->getIterator();
        $transport->uasort([OrderTransport::class, 'compare']);

        return view('pages.customer.components', [
            'order' => $order,
            'orders' => CustomerAuthenticationRepository::getCustomer()->orders,
            'orderCustomer' => $oCustomer,
            'editable' => self::getOrderCustomers($order, CustomerAuthenticationRepository::getCustomer()),
            'accommodation' => $accommodation,
            'activities' => $activities,
            'flights' => $flights,
            'transports' => $transport,
        ]);
    }

    public function purchaseExtra(string $reference, string $componentType, int $componentId, ?Customer $customer = null)
    {
        $order = StaticOrderRepository::getOrderFromBookingReference($reference);
        if (!isset($order) || $order->cancelled) abort(404);
        $customer = $customer ?? CustomerAuthenticationRepository::getCustomer();
        if (!isset($customer)) abort(404);
        if (CustomerAuthenticationRepository::getCustomer()->id != $customer->id) {
            if ($order->leadBooker->customer_id != CustomerAuthenticationRepository::getCustomer()->id) abort(404);
            if (isset($customer->email_address) && isset($customer->password)) abort(404);
        }
        $orderCustomer = $order->repository->getOrderCustomer($customer);
        if (!isset($orderCustomer)) abort(404);

        $tourComponent = $this->getComponent($componentType, $componentId);
        if (!isset($tourComponent)) abort(404);
        if (!$tourComponent->is_bookable) abort(404);

        if ($tourComponent->available_stock <= 0) abort(404);

        $data = [
            'additions' => [[
                'customer' => $componentType == 'accommodation' ? $orderCustomer->primary_group->id : $orderCustomer->customer->id,
                'component' => $componentType,
                'id' => $tourComponent->id,
        ],],];

        return StripeGateway::checkout(
            [['name' => $tourComponent->__toString(), 'cost' => $tourComponent->tour_sales_price, 'quantity' => 1]],
                $order->booking_reference, 'Installment', CustomerAuthenticationRepository::getCustomer()->id, $data);
    }

    public function addExtra(string $reference, string $componentType, int $componentId, ?Customer $customer = null)
    {
        $order = StaticOrderRepository::getOrderFromBookingReference($reference);
        if (!isset($order) || $order->cancelled) abort(404);
        $customer = $customer ?? CustomerAuthenticationRepository::getCustomer();
        if (!isset($customer)) abort(404);
        if (CustomerAuthenticationRepository::getCustomer()->id != $customer->id) {
            if ($order->leadBooker->customer_id != CustomerAuthenticationRepository::getCustomer()->id) abort(404);
        }
        $orderCustomer = $order->repository->getOrderCustomer($customer);
        if (!isset($orderCustomer)) abort(404);

        $tourComponent = $this->getComponent($componentType, $componentId);
        if (!isset($tourComponent)) abort(404);
        if (!$tourComponent->is_bookable) abort(404);

        if (!(SettingsRepository::getBoolean('payment.required', true))) abort(404);

        if ($tourComponent->available_stock <= 0) abort(404);

        $orderComponent  = $tourComponent->addToOrder($orderCustomer);

        if (!($tourComponent instanceof AccommodationInventoryTour)) {
            event(new OrderCustomerComponentAddedEvent($orderComponent));
        }

        return redirect()->route('customer.extras', ['reference' => $reference,]);
    }

    private function getComponent(string $componentType, int $componentId): ?Model
    {
        return match ($componentType) {
            'accommodation' => AccommodationInventoryTour::find($componentId),
            'activity' => ActivityInventoryTour::find($componentId),
            'flight' => FlightInventoryTour::find($componentId),
            'transport' => TransportInventoryTour::find($componentId),
            'extra' => Merchandise::find($componentId),
            default => null,
        };
    }

    public function updateNotes(Request $request, string $reference, OrderCustomer $orderCustomer)
    {
        $customer = CustomerAuthenticationRepository::getCustomer();
        if (!isset($customer)) abort(404);
        $order = StaticOrderRepository::getOrderFromBookingReference($reference);
        if (!isset($order) || $order->cancelled) abort(404);
        if (!$order->repository->isLeadBooker($customer)) abort(404);
        if ($order->repository->isLeadBooker(CustomerAuthenticationRepository::getCustomer())) {
            $order->update([
                'external_notes' => $request->input('order_notes'),
            ]);
            $order->save();
        }
        $orderCustomer->update([
            'external_notes' => $request->input('order_customer_notes'),
            'accommodation_notes' => $request->input('accommodation_notes'),
            'activity_notes' => $request->input('activity_notes'),
            'flight_notes' => $request->input('flight_notes'),
            'transport_notes' => $request->input('transport_notes'),
        ]);
        $orderCustomer->save();
        return redirect()->route('customer.itinerary', ['reference' => $reference,]);
    }

    private function getOrderCustomers(Order $order, Customer $customer): array
    {
        $orderCustomer = $order->repository->getOrderCustomer($customer);
        if ($order->lead_booker_id !== $orderCustomer->id) return [];
        $data = [$orderCustomer,];
        foreach ($order->orderCustomers as $oCustomer) {
            if (!isset($oCustomer->customer->email_address) || !isset($oCustomer->customer->password)) $data[] = $oCustomer;
        }
        return $data;
    }
}
