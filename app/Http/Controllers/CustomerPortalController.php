<?php

namespace App\Http\Controllers;

use App\Http\Gateways\StripeGateway;
use App\Models\Customer;
use App\Repository\OrderRepository;
use Auth;
use Illuminate\Http\Request;

class CustomerPortalController extends Controller
{
    private function getCustomer(): ?Customer
    {
        return Customer::find(Auth::guard('customer')->id());
    }

    public function showMainPortal(Customer $customer) {
        return view('pages.customer.portal', ['customer' => $this->getCustomer(),]);
    }

    public function showCustomerLogin() {
        return view('pages.customer.auth.login');
    }

    public function showCustomerRegister() {
        return view('pages.customer.auth.register');
    }

    public function showDetailsPage() {
        return view('pages.customer.details', ['customer' => $this->getCustomer(),]);
    }

    public function showAtol() {
        return view('pages.customer.atol', ['customer' => $this->getCustomer(),]);
    }

    public function showEditDetailsPage() {
        return view('pages.customer.edit', ['customer' => $this->getCustomer(),]);
    }

    public function showFinancesPage() {
        return view('pages.customer.finances', ['orders' => $this->getCustomer()->orders]);
    }

    public function login(Request $request) {
        if (Auth::guard('customer')->attempt(['email_address' => $request->email, 'password' => $request->password])) {
            return redirect()->intended(route('customer.portal'));
        }
        return back()->withErrors('Could not authenticate with those credentials')->withInput($request->only('email', 'remember'));
    }

    public function register(Request $request) {
        // TODO: (Celeste) Implement
        return redirect()->route('customer.portal');
    }

    public function storeDetails(Request $request, Customer $customer) {
        // TODO: (Celeste) Implement
        return redirect()->route('customer.details', ['customer' => $customer,]);
    }

    public function makePayment(Request $request)
    {
        $request->validate(['booking_reference' => 'required|exists:orders,booking_reference', 'amount' => 'required|numeric']);
        $order = OrderRepository::getOrderFromBookingReference($request->input('booking_reference'));
        $amount = $request->input('amount');
        if (!isset($order) || $order->leadBooker->customer->id != $this->getCustomer()->id) {
            return back()->withErrors('Cannot make a payment for an invalid order');
        }
        if ($amount > $order->remaining) {
            return back()->withErrors('Cannot pay more than you owe');
        }
<<<<<<< HEAD
        return StripeGateway::checkout([['name' => "Installment Payment ({$order->booking_reference})", 'quantity' => 1, 'cost' => $amount]], $order->booking_reference, 'Installment', $this->getCustomer()->id);
=======
        return StripeGateway::checkout([['name' => "Installment Payment ({$order->booking_reference})", 'quantity' => 1, 'cost' => $amount]], $order, 'Installment', $this->getCustomer()->id);
>>>>>>> main
    }
}
