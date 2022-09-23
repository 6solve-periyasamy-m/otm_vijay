<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Gateways\Storage\LineItem;
use App\Http\Gateways\StripeGateway;
use App\Models\Order\Payment\PaymentIntention;
use App\Repository\Authentication\CustomerAuthenticationRepository;
use App\Repository\Model\Order\OrderRepository;
use Gateway;
use Illuminate\Http\Request;

class CustomerFinancesController extends Controller
{
    public function show()
    {
        return view('pages.customer.finances', ['orders' => CustomerAuthenticationRepository::getCustomer()->orders]);
    }

    public function makePayment(Request $request)
    {
        $request->validate(['booking_reference' => 'required|exists:orders,booking_reference',]);
        $order = OrderRepository::getFromBookingReference($request->input('booking_reference'));
        $amount = sigfig(preg_replace('/[^0-9.]/', '', $request->amount));

        if (!isset($order) ||
            $order->repository->getOrderCustomer(CustomerAuthenticationRepository::getCustomer()) === null) {
            return back()->withErrors('Cannot make a payment for an invalid order');
        }
        if ($amount > $order->remaining) {
            return back()->withErrors('Cannot pay more than you owe');
        }
        if ($amount >= 1_000_000) {
            return back()->withErrors('We cannot process payments that large');
        }
        if ($amount <= 0.3) {
            return back()->withErrors('We cannot process payments that small');
        }

        $gateway = Gateway::getDefaultGateway();
        $redirect = setting('payment.success.redirect', route('payment.gateway.stripe.success'));

        $item = new LineItem("Installment Payment ({$order->booking_reference})", $amount);
        $intention = PaymentIntention::build(CustomerAuthenticationRepository::getCustomer(), $order->booking_reference, 'Installment');

        return redirect($gateway->checkout([$item,], $intention, CustomerAuthenticationRepository::getCustomer(), $redirect));
    }

    public function showInvoice(string $reference)
    {
        $order = OrderRepository::getFromBookingReference($reference);
        if (!isset($order)) {
            abort(404);
        }
        if ($order->repository->getOrderCustomer(CustomerAuthenticationRepository::getCustomer()) === null) {
            abort(404);
        }
        return $order->repository->getInvoiceRepository()->getResponseStream();
    }
}
