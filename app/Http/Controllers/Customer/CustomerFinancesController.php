<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Gateways\StripeGateway;
use App\Repository\CustomerAuthenticationRepository;
use App\Repository\OrderRepository;
use Illuminate\Http\Request;

class CustomerFinancesController extends Controller
{
    public function show()
    {
        return view('pages.customer.finances', ['orders' => CustomerAuthenticationRepository::getCustomer()->orders]);
    }

    public function makePayment(Request $request)
    {
        $request->validate(['booking_reference' => 'required|exists:orders,booking_reference', 'amount' => 'required|numeric']);
        $order = OrderRepository::getOrderFromBookingReference($request->input('booking_reference'));
        $amount = $request->input('amount');
        if (!isset($order) || $order->leadBooker->customer->id != CustomerAuthenticationRepository::getCustomer()->id) {
            return back()->withErrors('Cannot make a payment for an invalid order');
        }
        if ($amount > $order->remaining) {
            return back()->withErrors('Cannot pay more than you owe');
        }
        return StripeGateway::checkout([['name' => "Installment Payment ({$order->booking_reference})", 'quantity' => 1, 'cost' => $amount]], $order, 'Installment', CustomerAuthenticationRepository::getCustomer()->id);
    }

    public function showInvoice(string $reference)
    {
        $order = OrderRepository::getOrderFromBookingReference($reference);
        if (!isset($order)) {
            abort(404);
        }
        if (OrderRepository::isOrderCustomer($order, CustomerAuthenticationRepository::getCustomer())) {
            abort(404);
        }
        return view('pdf.invoices.columns', ['invoice' => OrderRepository::generateInvoice($order),]);
    }
}
