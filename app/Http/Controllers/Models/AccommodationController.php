<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Address;
use App\Models\AddressParent;
use App\Repository\LocationsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
        $request->validate(Accommodation::getValidationRules());
        $accommodation = Accommodation::make([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'audit_date' => $request->input('audit_date'),
            'currency_id' => $request->input('currency_id'),
        ]);
        if ($request->input('use_existing') == 'on') {
            $address = LocationsRepository::cloneAddressToAddress(Address::findOrFail($request->input('address_id')), AddressParent::getParentId('accommodation'));
        } else {
            $request->validate(Address::getValidationRules());
            $address = LocationsRepository::storeAddressFromGenericRequest(null, AddressParent::getParentId('accommodation'), $request, $request->input('name'), '');
        }
        if ($request->has('image') && $request->file('image') != null) {
            $accommodation->image_url = $request->file('image')->storePublicly('uploads/images');
        }

        $accommodation->address_id = $address->id;
        $accommodation->save();
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function view(Accommodation $accommodation)
    {
        return view('pages.components.accommodation', ['accommodation' => $accommodation,]);
    }

    public function edit(Accommodation $accommodation)
    {
        return view('pages.models.accommodations.update', ['accommodation' => $accommodation,]);
    }

    public function update(Request $request, Accommodation $accommodation)
    {
        $request->validate(Accommodation::getValidationRules());
        $accommodation->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'audit_date' => $request->input('audit_date'),
            'currency_id' => $request->input('currency_id'),
        ]);
        if ($request->input('use_existing') == 'on') {
            LocationsRepository::cloneAddressToAddress(Address::findOrFail($request->input('address_id')), AddressParent::getParentId('accommodation'), $accommodation->address);
        } else {
            $request->validate(Address::getValidationRules());
            LocationsRepository::storeAddressFromGenericRequest($accommodation->address, AddressParent::getParentId('accommodation'), $request, $request->input('name'));
        }
        if ($request->has('image') && $request->file('image') != null) {
            if (isset($accommodation->image_url)) {
                File::delete(public_path($accommodation->image_url));
            }
            $accommodation->image_url = $request->file('image')->storePublicly('uploads/images');
        }
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function destroy(Accommodation $accommodation)
    {
        foreach ($accommodation->inventory as $inventory) {
            if ($inventory->tourComponents()->count() > 0) {
                return back()->withErrors(trans('custom.used-in-tour', ['model' => 'Accommodation']));
            }
        }
        $accommodation->delete();
        return redirect()->route('accommodations.all');
    }
}
