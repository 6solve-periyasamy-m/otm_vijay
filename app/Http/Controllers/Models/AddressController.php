<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{

    public function index()
    {
        return view('pages.models.addresses.table', ['addresses' => Address::all(),]);
    }

    public function create()
    {
        return view('pages.models.addresses.create');
    }

    public function store(Request $request)
    {
        $request->validate(Address::getValidationRules());
        $address = Address::create([
            'address_line_1' => $request->input('address_line_1'),
            'address_line_2' => $request->input('address_line_2'),
            'town' => $request->input('town'),
            'region' => $request->input('region'),
            'country' => $request->input('country'),
            'postcode' => $request->input('postcode'),
        ]);
        return redirect()->route('addresses.view', ['address' => $address,]);
    }

    public function view(Address $address)
    {
        return view('pages.models.addresses.view', ['address' => $address,]);
    }

    public function edit(Address $address)
    {
        return view('pages.models.addresses.update', ['address' => $address,]);
    }

    public function update(Request $request, Address $address)
    {
        $request->validate(Address::getValidationRules());
        $address->update([
            'address_line_1' => $request->input('address_line_1'),
            'address_line_2' => $request->input('address_line_2'),
            'town' => $request->input('town'),
            'region' => $request->input('region'),
            'country' => $request->input('country'),
            'postcode' => $request->input('postcode'),
        ]);
        return redirect()->route('addresses.view', ['address' => $address,]);
    }

    public function destroy(Address $address)
    {
        $address->delete();
        return redirect()->route('addresses.all');
    }
}
