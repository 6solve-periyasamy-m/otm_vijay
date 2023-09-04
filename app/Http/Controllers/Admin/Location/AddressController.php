<?php

namespace App\Http\Controllers\Admin\Location;

use App\Http\Controllers\Controller;
use App\Models\Helper\AddressParent;
use App\Models\Location\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{

    public function index()
    {
        return view('pages.models.addresses.table', ['addresses' => Address::where('parent', '!=', AddressParent::CUSTOMER)->get(),]);
    }

    public function create(string $addressParent)
    {
        if (AddressParent::tryFrom($addressParent) === null) return back()->withErrors(['msg' => 'An invalid parent has been provided',]);
        return view('pages.models.addresses.create', ['addressParent' => $addressParent,]);
    }

    public function store(Request $request, string $addressParent)
    {
        if (AddressParent::tryFrom($addressParent) === null) return back()->withErrors(['msg' => 'An invalid parent has been provided',]);
        $request->validate(Address::getValidationRules());
        $address = Address::create([
            'name' => $request->input('name'),
            'location_type_id' => $request->input('location_type_id'),
            'parent' => $addressParent,
            'address_line_1' => $request->input('address_line_1'),
            'address_line_2' => $request->input('address_line_2'),
            'town' => $request->input('town'),
            'region' => $request->input('region'),
            'country_id' => $request->input('country_id'),
            'postcode' => $request->input('postcode'),
        ]);
        return view('pages.close');
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
            'name' => $request->input('name'),
            'location_type_id' => $request->input('location_type_id'),
            'address_line_1' => $request->input('address_line_1'),
            'address_line_2' => $request->input('address_line_2'),
            'town' => $request->input('town'),
            'region' => $request->input('region'),
            'country_id' => $request->input('country_id'),
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
