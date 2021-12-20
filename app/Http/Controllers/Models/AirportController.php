<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\AddressParent;
use App\Models\Airport;
use App\Repository\LocationsRepository;
use Illuminate\Http\Request;

class AirportController extends Controller
{

    public function index()
    {
        return view('pages.models.airports.table', ['airports' => Airport::all(),]);
    }

    public function create()
    {
        return view('pages.models.airports.create');
    }

    public function store(Request $request)
    {
        $request->validate(Airport::getValidationRules());
        $airport = Airport::make([
            'name' => $request->input('name'),
            'iata_code' => $request->input('iata_code'),
        ]);
        if ($request->input('use_existing') == 'on') {
            $address = LocationsRepository::cloneAddressToAddress(Address::findOrFail($request->input('address_id')), AddressParent::getParentId('airport'));
        } else {
            $request->validate(Address::getValidationRules());
            $address = LocationsRepository::storeAddressFromGenericRequest(null, AddressParent::getParentId('airport'), $request, $request->input('name'), '');
        }
        $airport->address_id = $address->id;
        $airport->save();
        return redirect()->route('airports.view', ['airport' => $airport,]);
    }

    public function view(Airport $airport)
    {
        return view('pages.models.airports.view', ['airport' => $airport,]);
    }

    public function edit(Airport $airport)
    {
        return view('pages.models.airports.update', ['airport' => $airport,]);
    }

    public function update(Request $request, Airport $airport)
    {
        $request->validate(Airport::getValidationRules());
        $airport->update([
            'name' => $request->input('name'),
            'iata_code' => $request->input('iata_code'),
        ]);
        if ($request->input('use_existing') == 'on') {
            LocationsRepository::cloneAddressToAddress(Address::findOrFail($request->input('address_id')), AddressParent::getParentId('airport'), $airport->address);
        } else {
            $request->validate(Address::getValidationRules());
            LocationsRepository::storeAddressFromGenericRequest($airport->address, AddressParent::getParentId('airport'), $request, $request->input('name'));
        }
        return redirect()->route('airports.view', ['airport' => $airport,]);
    }

    public function destroy(Airport $airport)
    {
        $airport->delete();
        return redirect()->route('airports.all');
    }
}
