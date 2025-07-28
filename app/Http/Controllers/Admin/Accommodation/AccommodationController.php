<?php

namespace App\Http\Controllers\Admin\Accommodation;

use App\Exports\Inventory\AccommodationInventoryExport;
use App\Http\Controllers\Abstract\ImportsToCollection;
use App\Http\Controllers\Controller;
use App\Imports\Inventory\AccommodationOverrideImport;
use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\Amenity;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Location\Address;
use App\Repository\Model\Location\AddressRepository;
use App\Repository\Reporting\Manifest\RoomingReportRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AccommodationController extends Controller
{
    use ImportsToCollection;

    public function index(Request $request)
    {
        $archived = $request->archived ?? false;
        return view('pages.admin.accommodation.table', ['archived' => $archived,]);
    }

    public function create()
    {
        $amenities = Amenity::select('id', 'name')->get();
        return view('pages.admin.accommodation.form', compact('amenities'));
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
            'check_in' => $request->input('check_in'),
            'check_out' => $request->input('check_out'),
            'accommodation_type_id' => $request->input('accommodation_type_id'),
            'additional_description' => $request->input('additional_description'),
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
        if ($request->has('amenities')) {
            $accommodation->amenities()->sync($request->amenities ?? []);
        }
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->storePublicly('uploads/images');
                $accommodation->media()->create([
                    'file_path' => $path,
                    'type' => 'gallery',
                ]);
            }
        }
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function view(Accommodation $accommodation)
    {
        return view('pages.admin.accommodation.view', ['accommodation' => $accommodation,]);
    }

    public function exportInventory(Accommodation $accommodation)
    {
        return (new AccommodationInventoryExport($accommodation))->download();
    }

    public function importInventory(Request $request, Accommodation $accommodation): RedirectResponse
    {
        return $this->import((new AccommodationOverrideImport($accommodation)), $request->file('import'));
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
        $amenities = Amenity::select('id', 'name')->get();
        $selected_amenities = $accommodation->amenities->pluck('id')->toArray();
        return view('pages.admin.accommodation.form', compact('accommodation', 'amenities', 'selected_amenities'));
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
            'check_in' => $request->input('check_in'),
            'check_out' => $request->input('check_out'),
            'accommodation_type_id' => $request->input('accommodation_type_id'),
            'additional_description' => $request->input('additional_description'),
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
        if ($request->has('amenities')) {
            $accommodation->amenities()->sync($request->amenities ?? []);
        }
        if ($request->hasFile('gallery')) {
            $accommodation->gallery()->delete();
            foreach ($request->file('gallery') as $image) {
                $path = $image->storePublicly('uploads/images');
                $accommodation->media()->create([
                    'file_path' => $path,
                    'type' => 'gallery',
                ]);
            }
        }
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function duplicate(Accommodation $accommodation): RedirectResponse
    {
        $duplicate = $accommodation->repository->duplicate(true);
        return redirect()->route('accommodations.view', ['accommodation' => $duplicate,]);
    }

    public function archive(Accommodation $accommodation): RedirectResponse
    {
        $accommodation->archived = !$accommodation->archived;
        $accommodation->save();

        if (!$accommodation->archived) {
            return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
        }

        return redirect()->route('accommodations.all');
    }
}
