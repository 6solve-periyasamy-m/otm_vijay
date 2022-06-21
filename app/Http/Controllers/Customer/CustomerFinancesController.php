<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Gateways\StripeGateway;
use App\Repository\Authentication\CustomerAuthenticationRepository;
use App\Repository\Model\Order\OrderRepository;
use Illuminate\Http\Request;

class CustomerFinancesController extends Controller
{
    public function show()
    {
        return view('pages.customer.finances', ['orders' => CustomerAuthenticationRepository::getCustomer()->orders]);
    }

    public function makePayment(Request $request)
    {
        $request->validate(['booking_reference' => 'required|exists:orders,booking_reference', 'amount' => 'required|numeric|min:0.3|max:999999.99']);
        $order = OrderRepository::getFromBookingReference($request->input('booking_reference'));
        $amount = $request->input('amount');
        if (!isset($order) ||
            $order->repository->getOrderCustomer(CustomerAuthenticationRepository::getCustomer()) === null) {
            return back()->withErrors('Cannot make a payment for an invalid order');
        }
        if ($amount > $order->remaining) {
            return back()->withErrors('Cannot pay more than you owe');
        }
        return StripeGateway::checkout([['name' => "Installment Payment ({$order->booking_reference})", 'quantity' => 1, 'cost' => $amount]], $order->booking_reference, 'Installment', CustomerAuthenticationRepository::getCustomer()->id);
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
