<?php

namespace App\Http\Controllers\Customer;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Exceptions\RemoteGatewayError;
use App\Exceptions\UnauthorizedGatewayException;
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
use App\Models\Transport\TransportInventoryTour;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Intention\PaymentIntentionRepository;
use App\Repository\Intention\Storage\AdditionIntention;
use Gateway;
use Spatie\Browsershot\Browsershot;

class CustomerTourController extends CustomerController
{
    private function getOrderCustomer(?Order $reference = null, ?Customer $customer = null): OrderCustomer|null
    {
        $customer = $customer ?? $this->user();
        $order = $reference ??  $customer->repository->getDefaultOrder();
        if (!$this->user()->repository->canEditCustomer($customer)) abort(404);
        if (!isset($order)) abort(404);
        $oCustomer = null;
        foreach ($order->orderCustomers as $orderCustomer) {
            if ($orderCustomer->customer_id == $customer->id) {
                $oCustomer = $orderCustomer;
                break;
            }
        }
        return $oCustomer;
    }

    public function showItinerary(?Order $reference = null, ?Customer $customer = null)
    {
        $orderCustomer = $this->getOrderCustomer($reference, $customer);
        if (!isset($orderCustomer)) abort(404);
        return view('pages.customer.itinerary', [
            'orderCustomer' => $orderCustomer,
            'order' => $orderCustomer->order,
            'orders' => $this->user()->orders,
            'editable' => self::getOrderCustomers($orderCustomer->order, $this->user()),
        ]);
    }

    public function downloadItinerary(?Order $reference = null, ?Customer $customer = null)
    {
        $orderCustomer = $this->getOrderCustomer($reference, $customer);
        if (!isset($orderCustomer)) abort(404);$invoice = Browsershot::html(view('pdf.itinerary', ['orderCustomer' => $orderCustomer,])->render())->noSandbox();
        $invoice->showBackground()->margins(10, 2, 10, 2);
        return response()->stream(function () use ($invoice) { echo $invoice->pdf(); }, 200, ['Content-Type' => 'application/pdf']);

    }

    public function showExtras(?Order $reference = null, ?Customer $customer = null)
    {
        $orderCustomer = $this->getOrderCustomer($reference, $customer);
        if (!isset($orderCustomer)) abort(404);

        $accommodation = collect($orderCustomer->orderAccommodation)->getIterator();
        $accommodation->uasort([OrderAccommodation::class, 'compare']);
        $activities = $orderCustomer->orderActivities->getIterator();
        $activities->uasort([OrderActivity::class, 'compare']);
        $flights = $orderCustomer->orderFlights->getIterator();
        $flights->uasort([OrderFlight::class, 'compare']);
        $transport = $orderCustomer->orderTransports->getIterator();
        $transport->uasort([OrderTransport::class, 'compare']);

        return view('pages.customer.components', [
            'order' => $orderCustomer->order,
            'orders' => $this->user()->orders,
            'orderCustomer' => $orderCustomer,
            'editable' => self::getOrderCustomers($orderCustomer->order, $this->user()),
            'accommodation' => $accommodation,
            'activities' => $activities,
            'flights' => $flights,
            'transports' => $transport,
        ]);
    }

    public function purchaseExtra(Order $order, string $componentType, int $componentId, ?Customer $customer = null)
    {
        if ($order->cancelled) abort(404);
        $customer = $customer ?? $this->user();
        if (!isset($customer)) abort(404);
        if ($this->user()->id != $customer->id) {
            if ($order->leadBooker->customer_id != $this->user()->id) abort(404);
            if (isset($customer->email_address) && isset($customer->password)) abort(404);
        }
        $orderCustomer = $order->repository->getOrderCustomer($customer);
        if (!isset($orderCustomer)) abort(404);

        /** @var InventoryTourRepository $tourComponent */
        $tourComponent = InventoryTourRepository::getComponent($componentType, $componentId);
        if (!isset($tourComponent)) abort(404);
        if (!$tourComponent->isBookable()) abort(404);
        if (!$tourComponent->hasEnoughStock()) abort(404);

        $redirect = setting('purchase.addon.success.redirect', url()->previous(route('customer.extras', ['reference' => $order->booking_reference, 'customer' => $customer,])));

        if ($tourComponent->get()->tour_sales_price > 0) {
            $data = AdditionIntention::create($orderCustomer, $tourComponent);

            $item = new LineItem("{$tourComponent}", $tourComponent->get()->tour_sales_price);
            $intention = PaymentIntentionRepository::create($order, $orderCustomer->customer, 'Installment', [$data,]);

            try {
                return redirect(Gateway::getDefaultGateway()->checkout([$item,], $intention, $this->user(), $redirect));
            } catch (UnauthorizedGatewayException $e) {
                return back()->withErrors(['msg' => 'Something went wrong with our payment processing. Please try again later.']);
            } catch (RemoteGatewayError $e) {
                return back()->withErrors(['msg' => 'Something went wrong with our 3rd-party payment processing. Please try again later.']);
            }
        } else {
            $tourComponent->grantToCustomer($orderCustomer);
            return redirect($redirect);
        }
    }

    public function addExtra(Order $order, string $componentType, int $componentId, ?Customer $customer = null)
    {
        if ($order->cancelled) abort(404);
        $customer = $customer ?? $this->user();
        if (!isset($customer)) abort(404);
        if ($this->user()->id != $customer->id) {
            if ($order->leadBooker->customer_id != $this->user()->id) abort(404);
        }
        $orderCustomer = $order->repository->getOrderCustomer($customer);
        if (!isset($orderCustomer)) abort(404);

        /** @var AccommodationInventoryTour|ActivityInventoryTour|FlightInventoryTour|TransportInventoryTour $tourComponent */
        $tourComponent = InventoryTourRepository::getComponent($componentType, $componentId);
        if (!isset($tourComponent)) abort(404);
        if (!$tourComponent->isBookable()) abort(404);

        if ($tourComponent->hasEnoughStock()) abort(404);

        if (flag('payment.required', true)) abort(404);

        if ($tourComponent->getAvailableStock() <= 0) abort(404);

        $orderComponent = $tourComponent->grantToCustomer($orderCustomer);

        if (!($tourComponent instanceof AccommodationInventoryTour)) {
            /** @var OrderActivity|OrderFlight|OrderTransport|null $orderComponent */
            event(new OrderCustomerComponentAddedEvent($orderComponent->get()));
        }

        return redirect()->route('customer.extras', ['reference' => $order->booking_reference,]);
    }

    public function updateNotes(TourDetailsRequest $request, Order $reference, OrderCustomer $orderCustomer)
    {
        $customer = $this->user();
        if (!isset($customer)) abort(404);
        $order = $reference;

        if (!isset($order) || $order->cancelled) abort(404);
        if (!$order->repository->isLeadBooker($customer)) abort(404);

        if ($order->repository->isLeadBooker($this->user())) {
            $order->update(['external_notes' => $request->order_notes,]);
            $order->save();
        }
        $details = $request->getOrderCustomerDetails();
        if ($order->tour->repository->isOrderNotesLocked()) { unset($details['order_notes']); unset($details['order_customer_notes']); }
        if ($order->tour->repository->isAccommodationLocked()) { unset($details['accommodation_notes']); }
        if ($order->tour->repository->isActivityLocked()) { unset($details['activity_notes']); }
        if ($order->tour->repository->isFlightLocked()) { unset($details['flight_notes']); }
        if ($order->tour->repository->isTransportLocked()) { unset($details['transport_notes']); }
        $orderCustomer->repository->update($details);
        return redirect()->route('customer.itinerary', ['reference' => $order->booking_reference,]);
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
