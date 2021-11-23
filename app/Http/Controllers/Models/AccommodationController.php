<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Address;
use App\Models\AddressParent;
use App\Repository\LocationsRepository;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{

    public function index()
    {
        return view('pages.models.accommodations.table', ['accommodations' => Accommodation::all(),]);
    }

    public function create()
    {
        return view('pages.models.accommodations.create');
    }

    public function store(Request $request)
    {
        $request->validate(Accommodation::RULES);
        $accommodation = Accommodation::make([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'audit_date' => $request->input('audit_date'),
            'currency_id' => $request->input('currency_id'),
        ]);
        if ($request->input('use_existing') == 'on') {
            $address = LocationsRepository::cloneAddressToAddress(Address::findOrFail($request->input('address_id')), AddressParent::getParentId('accommodation'));
        } else {
            $address = LocationsRepository::storeAddressFromGenericRequest(null, AddressParent::getParentId('accommodation'), $request, $request->input('name'), '');
        }
        $accommodation->address_id = $address->id;
        $accommodation->save();
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function view(Accommodation $accommodation)
    {
        return view('pages.components.accommodation', ['accommodation' => $accommodation, ]);
    }

    public function edit(Accommodation $accommodation)
    {
        return view('pages.models.accommodations.update', ['accommodation' => $accommodation,]);
    }

    public function update(Request $request, Accommodation $accommodation)
    {
        $request->validate(Accommodation::RULES);
        $accommodation->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'audit_date' => $request->input('audit_date'),
            'currency_id' => $request->input('currency_id'),
        ]);
        if ($request->input('use_existing') == 'on') {
            LocationsRepository::cloneAddressToAddress(Address::findOrFail($request->input('address_id')), AddressParent::getParentId('accommodation'), $accommodation->address);
        } else {
            LocationsRepository::storeAddressFromGenericRequest($accommodation->address, AddressParent::getParentId('accommodation'), $request, $request->input('name'));
        }
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function destroy(Accommodation $accommodation)
    {
        $accommodation->delete();
        return redirect()->route('accommodations.all');
    }
}
