<?php

namespace App\Http\Controllers;

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
        //echo json_encode(OrderRepository::getCustomerOrders($customer));
        return view('pages.customer.finances', OrderRepository::getCustomerOrders($this->getCustomer()));
    }

    public function login(Request $request) {
        if (Auth::guard('customer')->attempt(['email_address' => $request->email, 'password' => $request->password])) {
            return redirect()->intended(route('customer.portal'));
        }
        return back()->withErrors('Could not authenticate with those credentials');
    }

    public function register(Request $request) {
        // TODO: (Celeste) Implement
        return redirect()->route('customer.portal');
    }

    public function storeDetails(Request $request, Customer $customer) {
        // TODO: (Celeste) Implement
        return redirect()->route('customer.details', ['customer' => $customer,]);
    }
}
