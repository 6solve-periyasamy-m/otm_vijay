<?php

namespace App\Http\Controllers\Admin\Flight;

use App\Http\Controllers\Controller;
use App\Models\Flight\Airport;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Location\Address;
use App\Repository\Model\Location\AddressRepository;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function create()
    {
        return view('pages.admin.flight.airport.form');
    }

    public function store(Request $request)
    {
        $request->validate(Airport::getValidationRules());
        $airport = Airport::make([
            'name' => $request->input('name'),
            'iata_code' => $request->input('iata_code'),
        ]);
        if ($request->input('use_existing') == 'on') {
            $address = Address::findOrFail($request->input('address_id'))->repository->cloneToNew(AddressParent::AIRPORT);
        } else {
            $request->validate(Address::getValidationRules());
            $address = new Address(AddressRepository::getArrayFromGenericRequest($request, $request->input('address_name'), AddressParent::AIRPORT));
            $address->repository->save();
        }
        $airport->address_id = $address->id;
        $airport->save();
        return view('pages.close');
    }

    public function edit(Airport $airport)
    {
        return view('pages.admin.flight.airport.form', ['airport' => $airport,]);
    }

    public function update(Request $request, Airport $airport)
    {
        $request->validate(Airport::getValidationRules($airport->id));
        $airport->update([
            'name' => $request->input('name'),
            'iata_code' => $request->input('iata_code'),
        ]);
        if ($request->input('use_existing') == 'on') {
            Address::findOrFail($request->input('address_id'))->repository->cloneToNew(AddressParent::AIRPORT, $airport->address);
        } else {
            $request->validate(Address::getValidationRules());
            $airport->address->update(AddressRepository::getArrayFromGenericRequest($request, $request->input('address_name'), AddressParent::AIRPORT));
        }
        return view('pages.close');
    }

    public function destroy(Airport $airport)
    {
        $repo = $airport->repository;
        $repo->delete();
        return $repo->getReturnURL();
    }
}
