<?php

namespace App\Http\Controllers\Admin\Accommodation;

use App\Http\Controllers\Controller;
use App\Models\Accommodation\Accommodation;
use App\Models\Helper\AddressParent;
use App\Models\Location\Address;
use App\Repository\Model\Location\AddressRepository;
use App\Repository\Reporting\Manifest\RoomingReportRepository;
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
            'internal_notes' => $request->input('notes'),
        ]);
        if ($request->input('use_existing') == 'on') {
            $address = Address::findOrFail($request->input('address_id'))->repository->cloneToNew(AddressParent::ACCOMMODATION);
        } else {
            $request->validate(Address::getValidationRules());
            $address = new Address(AddressRepository::getArrayFromGenericRequest($request, $request->input('address_name'), AddressParent::ACCOMMODATION));
            $address->repository->save();
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
        return view('pages.admin.accommodation.view', ['accommodation' => $accommodation,]);
    }

    public function rooming(Request $request, Accommodation $accommodation)
    {
        $notes = !$request->has('notes') || $request->notes == true;
        return RoomingReportRepository::viewReport($accommodation->repository, 'accommodations.rooming.export', $notes, ['accommodation' => $accommodation,]);
    }

    public function exportRooming(Request $request, Accommodation $accommodation, string $extension)
    {
        $notes = !$request->has('notes') || $request->notes == true;
        return RoomingReportRepository::exportReport($accommodation->repository, $extension, $notes);
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
            'internal_notes' => $request->input('notes'),
        ]);
        if ($request->input('use_existing') == 'on') {
            Address::findOrFail($request->input('address_id'))->repository->cloneToNew(AddressParent::ACCOMMODATION, $accommodation->address);
        } else {
            $request->validate(Address::getValidationRules());
            $accommodation->address->repository->update(AddressRepository::getArrayFromGenericRequest($request, $request->input('address_name'), AddressParent::ACCOMMODATION));
        }
        if ($request->has('image') && $request->file('image') != null) {
            if (isset($accommodation->image_url)) {
                File::delete(public_path($accommodation->image_url));
            }
            $accommodation->image_url = $request->file('image')->storePublicly('uploads/images');
        }
        $accommodation->save();
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
