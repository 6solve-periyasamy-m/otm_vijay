<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Repository\OrderRepository;
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

    public function showAtol(Customer $customer) {
        return view('pages.customer.atol', ['customer' => $customer,]);
    }

    public function showEditDetailsPage(Customer $customer) {
        return view('pages.customer.edit', ['customer' => $customer,]);
    }

    public function showFinancesPage(Customer $customer) {
        //echo json_encode(OrderRepository::getCustomerOrders($customer));
        return view('pages.customer.finances', OrderRepository::getCustomerOrders($customer));
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
