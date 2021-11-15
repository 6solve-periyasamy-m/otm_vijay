<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerPortalController extends Controller
{
    public function showMainPortal(Customer $customer) {
        return view('pages.customer.portal', ['customer' => $customer,]);
    }

    public function showCustomerLogin() {
        return view('pages.customer.auth.login');
    }

    public function showCustomerRegister() {
        return view('pages.customer.auth.register');
    }

    public function showDetailsPage(Customer $customer) {
        return view('pages.customer.details', ['customer' => $customer,]);
    }

    public function showEditDetailsPage(Customer $customer) {
        return view('pages.customer.edit', ['customer' => $customer,]);
    }

    public function showFinancesPage(Customer $customer) {
        // TODO: (Celeste) Optimize
        $orders = [];
        foreach ($customer->orderCustomers() as $orderCustomer) {
            $orders[$orderCustomer->order->id] = $orderCustomer->order;
        }
        return view('pages.customer.finances', ['customer' => $customer, 'orders' => $orders, 'orderCustomers' => $customer->orderCustomers(),]);
    }

    public function login(Request $request) {
        // TODO: (Celeste) Implement
        return redirect()->route('customer.portal', ['customer' => Customer::findOrFail(1),]);
    }

    public function register(Request $request) {
        // TODO: (Celeste) Implement
        return redirect()->route('customer.portal', ['customer' => Customer::findOrFail(1),]);
    }

    public function storeDetails(Request $request, Customer $customer) {
        // TODO: (Celeste) Implement
        return redirect()->route('customer.details', ['customer' => $customer,]);
    }
}
