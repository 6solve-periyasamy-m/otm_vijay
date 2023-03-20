<?php

namespace App\Http\Controllers\Customer;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Http\Controllers\CustomerController;
use App\Http\Gateways\Storage\LineItem;
use App\Http\Requests\Customer\TourDetailsRequest;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Customer\Customer;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\Payment\PaymentIntention;
use App\Models\Transport\TransportInventoryTour;
use App\Repository\Abstracts\InventoryTourRepository;
use Gateway;

class CustomerTourController extends CustomerController
{
    public function showItinerary(?string $reference = null, ?Customer $customer = null)
    {
        $customer = $customer ?? $this->user;
        if (isset($reference)) {
            $order = $this->fetchOrder($reference);
        } else {
            $order = $customer->orders()->orderByDesc('ordered_on')->first();
        }
        if (!$this->user->repository->canEditCustomer($customer)) abort(404);
        if (!isset($order)) abort(404);
        $oCustomer = null;
        foreach ($order->orderCustomers as $orderCustomer) {
            if ($orderCustomer->customer_id == $customer->id) {
                $oCustomer = $orderCustomer;
                break;
            }
        }
        if (!isset($oCustomer)) abort(404);
        return view('pages.customer.itinerary', [
            'itinerary' => $oCustomer->repository->getItinerary(),
            'orderCustomer' => $oCustomer,
            'order' => $order,
            'orders' => $this->user->orders,
            'editable' => self::getOrderCustomers($order, $this->user),
        ]);
    }

    public function showExtras(?string $reference = null, ?Customer $customer = null)
    {
        $customer = $customer ?? $this->user;
        if (isset($reference)) {
            $order = $this->fetchOrder($reference);
        } else {
            foreach ($customer->orders()->orderBy('ordered_on', 'desc')->get() as $o) {
                if (!$o->cancelled) {
                    $order = $o;
                    break;
                }
            }
        }
        if (!isset($order) || $order->cancelled) abort(404);
        if ($this->user->id != $customer->id) {
            if ($order->leadBooker->customer_id != $this->user->id) abort(404);
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

        $accommodation = collect($oCustomer->orderAccommodation)->getIterator();
        $accommodation->uasort([OrderAccommodation::class, 'compare']);
        $activities = $oCustomer->orderActivities->getIterator();
        $activities->uasort([OrderActivity::class, 'compare']);
        $flights = $oCustomer->orderFlights->getIterator();
        $flights->uasort([OrderFlight::class, 'compare']);
        $transport = $oCustomer->orderTransports->getIterator();
        $transport->uasort([OrderTransport::class, 'compare']);

        return view('pages.customer.components', [
            'order' => $order,
            'orders' => $this->user->orders,
            'orderCustomer' => $oCustomer,
            'editable' => self::getOrderCustomers($order, $this->user),
            'accommodation' => $accommodation,
            'activities' => $activities,
            'flights' => $flights,
            'transports' => $transport,
        ]);
    }

    public function purchaseExtra(string $reference, string $componentType, int $componentId, ?Customer $customer = null)
    {
        $order = $this->fetchOrder($reference);
        if (!isset($order) || $order->cancelled) abort(404);
        $customer = $customer ?? $this->user;
        if (!isset($customer)) abort(404);
        if ($this->user->id != $customer->id) {
            if ($order->leadBooker->customer_id != $this->user->id) abort(404);
            if (isset($customer->email_address) && isset($customer->password)) abort(404);
        }
        $orderCustomer = $order->repository->getOrderCustomer($customer);
        if (!isset($orderCustomer)) abort(404);

        /** @var AccommodationInventoryTour|ActivityInventoryTour|FlightInventoryTour|TransportInventoryTour $tourComponent */
        $tourComponent = InventoryTourRepository::getComponent($componentType, $componentId)->get();
        if (!isset($tourComponent)) abort(404);
        if (!$tourComponent->is_bookable) abort(404);

        if ($tourComponent->available_stock <= 0) abort(404);

        $data = [
            'additions' => [
                [
                    'customer' => $componentType == 'accommodation' ? $orderCustomer->primary_group->id : $orderCustomer->id,
                    'component' => $componentType,
                    'id' => $tourComponent->id,
                ],
            ],
        ];

        $redirect = setting('purchase.addon.success.redirect', url()->previous(route('customer.extras', ['reference' => $reference, 'customer' => $customer,])));


        $item = new LineItem("{$tourComponent}", $tourComponent->tour_sales_price);
        $intention = PaymentIntention::build($this->user, $order->booking_reference, 'Installment', $data);

        return redirect(Gateway::getDefaultGateway()->checkout([$item,], $intention, $this->user, $redirect));
    }

    public function addExtra(string $reference, string $componentType, int $componentId, ?Customer $customer = null)
    {
        $order = $this->fetchOrder($reference);
        if (!isset($order) || $order->cancelled) abort(404);
        $customer = $customer ?? $this->user;
        if (!isset($customer)) abort(404);
        if ($this->user->id != $customer->id) {
            if ($order->leadBooker->customer_id != $this->user->id) abort(404);
        }
        $orderCustomer = $order->repository->getOrderCustomer($customer);
        if (!isset($orderCustomer)) abort(404);

        /** @var AccommodationInventoryTour|ActivityInventoryTour|FlightInventoryTour|TransportInventoryTour $tourComponent */
        $tourComponent = InventoryTourRepository::getComponent($componentType, $componentId)->get();
        if (!isset($tourComponent)) abort(404);
        if (!$tourComponent->is_bookable) abort(404);

        if (flag('payment.required', true)) abort(404);

        if ($tourComponent->available_stock <= 0) abort(404);

        $orderComponent = $tourComponent->repository->grantToCustomer($orderCustomer);

        if (!($tourComponent instanceof AccommodationInventoryTour)) {
            /** @var OrderActivity|OrderFlight|OrderTransport|null $orderComponent */
            event(new OrderCustomerComponentAddedEvent($orderComponent));
        }

        return redirect()->route('customer.extras', ['reference' => $reference,]);
    }

    public function updateNotes(TourDetailsRequest $request, string $reference, OrderCustomer $orderCustomer)
    {
        $customer = $this->user;
        if (!isset($customer)) abort(404);
        $order = $this->fetchOrder($reference);

        if (!isset($order) || $order->cancelled) abort(404);
        if (!$order->repository->isLeadBooker($customer)) abort(404);

        if ($order->repository->isLeadBooker($this->user)) {
            $order->update(['external_notes' => $request->order_notes,]);
            $order->save();
        }

        $orderCustomer->repository->update($request->getOrderCustomerDetails());
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
