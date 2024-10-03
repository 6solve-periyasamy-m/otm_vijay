<?php

namespace App\Http\Controllers\Admin\Accommodation;

use App\Exceptions\CannotDeleteException;
use App\Exports\Identifier\AccommodationInventoryExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Accommodation\AccommodationInventoryRequest;
use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Repository\Reporting\Manifest\RoomingReportRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccommodationInventoryController extends Controller
{
    public function create(Accommodation $accommodation)
    {
        return view('pages.admin.accommodation.inventory.form', ['accommodation' => $accommodation,]);
    }

    public function store(AccommodationInventoryRequest $request, Accommodation $accommodation)
    {
        $accommodation->inventory()->save(AccommodationInventory::make($request->getData()));
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function rooming(Request $request, Accommodation $accommodation, AccommodationInventory $inventory)
    {
        $notes = !$request->has('notes') || $request->notes == true;
        return RoomingReportRepository::viewReport($inventory->repository, 'accommodation-inventories.rooming.export', $notes, ['accommodation' => $accommodation, 'inventory' => $inventory,]);
    }

    public function exportRooming(Request $request, Accommodation $accommodation, AccommodationInventory $inventory, string $extension)
    {
        $notes = !$request->has('notes') || $request->notes == true;
        return RoomingReportRepository::exportReport($inventory->repository, $extension, $notes);
    }

    public function edit(Accommodation $accommodation, AccommodationInventory $inventory)
    {
        return view('pages.admin.accommodation.inventory.form', ['accommodation' => $accommodation, 'inventory' => $inventory,]);
    }

    public function update(AccommodationInventoryRequest $request, Accommodation $accommodation, AccommodationInventory $inventory)
    {
        if (!$inventory->repository->validateParent($request->stock_parent_id)) {
            return back()->withErrors(['msg' => "You cannot use this stock parent, as it is a child of this inventory"]);
        }
        $inventory->update($request->getData());
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function destroy(Accommodation $accommodation, AccommodationInventory $inventory): RedirectResponse
    {
        try {
            $inventory->repository->delete();
        } catch (CannotDeleteException $e) {
            return back()->withErrors($e->getMessage());
        }
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function duplicate(Accommodation $accommodation, AccommodationInventory $inventory)
    {
        $inventory = $inventory->replicate();
        $inventory->save();
        return redirect()->route('accommodation-inventories.edit', ['accommodation' => $accommodation, 'inventory' => $inventory,]);
    }

    public function exportIdentifier()
    {
        return new AccommodationInventoryExport();
    }
}
