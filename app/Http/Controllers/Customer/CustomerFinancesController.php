<?php

namespace App\Http\Controllers\Customer;

use App\Exceptions\RemoteGatewayError;
use App\Exceptions\UnauthorizedGatewayException;
use App\Http\Controllers\CustomerController;
use App\Http\Gateways\Storage\LineItem;
use App\Http\Requests\Customer\FinancesRequest;
use App\Models\Order\Order;
use App\Models\Order\Payment\PaymentIntention;
use App\Repository\Model\Order\OrderRepository;
use Gateway;

class CustomerFinancesController extends CustomerController
{
    public function show()
    {
        return view('pages.customer.finances', ['orders' => $this->user()->orders()->orderBy('cancelled', 'asc')->get() ]);
    }

    public function makePayment(FinancesRequest $request)
    {
        $request->validate(['booking_reference' => 'required|exists:orders,booking_reference', 'amount' => 'required',]);
        $order = OrderRepository::getFromBookingReference($request->input('booking_reference'));

        if (!isset($order) ||
            $order->repository->getOrderCustomer($this->user()) === null) {
            return back()->withErrors('Cannot make a payment for an invalid order');
        }
        if ($request->amount > $order->remaining) {
            return back()->withErrors('Cannot pay more than you owe');
        }

        $gateway = Gateway::getDefaultGateway();
        $redirect = setting('payment.success.redirect', route('payment.gateway.stripe.success'));

        $item = new LineItem("Installment Payment ({$order->booking_reference})", $request->amount);
        $intention = PaymentIntention::build($this->user(), $order->booking_reference, 'Installment');

        try {
            return redirect($gateway->checkout([$item,], $intention, $this->user(), $redirect));
        } catch (UnauthorizedGatewayException $e) {
            return back()->withErrors(['msg' => 'Something went wrong with our payment processing. Please try again later.']);
        } catch (RemoteGatewayError $e) {
            return back()->withErrors(['msg' => 'Something went wrong with our 3rd-party payment processing. Please try again later.']);
        }
    }

    public function showInvoice(Order|string $reference)
    {
        $order = $reference instanceof Order ?  $reference : OrderRepository::getFromBookingReference($reference);
        if (!isset($order)) {
            abort(404);
        }
        if ($order->repository->getOrderCustomer($this->user()) === null) {
            abort(404);
        }
        return $order->repository->getInvoiceRepository()->getResponseStream();
    }
}
