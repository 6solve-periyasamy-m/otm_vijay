<?php

namespace App\Http\Controllers;

use App\Http\Gateways\StripeGateway;
use App\Models\Customer;
use App\Models\Order;
use App\Repository\OrderRepository;
use Auth;
use Illuminate\Http\Request;
use App\Repository\BookingRepository;
use Illuminate\Validation\Rules\Password;

class CustomerPortalController extends Controller
{
    private function getCustomer(): ?Customer
    {
        return Customer::find(Auth::guard('customer')->id());
    }

    private function getRegistrationValidationRules(): array
    {
        return [
            'email' => 'required|exists:customers,email_address|email:rfc,dns',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
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

    // Customers can only register if they have a booking
    public function register(Request $request) {
        $customer = Customer::where('email_address', '=', $request->input('email'))->first();
        if (!isset($customer)) return back()->withErrors(['msg' => 'No bookings found with that email address',]);
        if (isset($customer->password)) return back()->withErrors(['msg' => 'That email address is already registered',]);
        $request->validate($this->getRegistrationValidationRules());
        $customer->update([
            'password' => \Hash::make($request->input('password')),
        ]);
        $customer->save();
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
        return StripeGateway::checkout([['name' => "Installment Payment ({$order->booking_reference})", 'quantity' => 1, 'cost' => $amount]], $order, 'Installment', $this->getCustomer()->id);
    }

    public function showInvoice(string $reference)
    {
        $order = OrderRepository::getOrderFromBookingReference($reference);
        if (!isset($order)) {
            abort(404);
        }
        if ($this->getCustomer()->id != $order->lead_booker_id) {
            abort(404);
        }
        return view('pdf.invoices.columns', ['invoice' => OrderRepository::generateInvoice($order),]);
    }
}
