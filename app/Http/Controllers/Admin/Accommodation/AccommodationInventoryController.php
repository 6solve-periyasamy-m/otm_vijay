<?php

namespace App\Http\Controllers\Admin\Accommodation;

use App\Http\Controllers\Controller;
use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Repository\Reporting\Manifest\RoomingReportRepository;
use Illuminate\Http\Request;

class AccommodationInventoryController extends Controller
{
    public function create(Accommodation $accommodation)
    {
        return view('pages.admin.accommodation.inventory.form', ['accommodation' => $accommodation,]);
    }

    public function store(Request $request, Accommodation $accommodation)
    {
        $request->validate(AccommodationInventory::getValidationRules());
        $inventory = AccommodationInventory::make([
            'room_type_id' => $request->input('room_type_id'),
            'board_type_id' => $request->input('board_type_id'),
            'stock_parent_id' => $request->input('stock_parent_id'),
            'check_in' => $request->input('check_in'),
            'check_in_time_confirmed' => $request->input('check_in_time_confirmed') == 'on' ? 1 : 0,
            'check_out' => $request->input('check_out'),
            'check_out_time_confirmed' => $request->input('check_out_time_confirmed') == 'on' ? 1 : 0,
            'fit_selectable' => $request->input('fit_selectable') == 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price') ?? 0,
            'sales_price' => $request->input('sales_price') ?? 0,
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
        ]);
        $accommodation->inventory()->save($inventory);
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function rooming(Request $request, Accommodation $accommodation, AccommodationInventory $inventory)
    {
        $notes = !$request->has('notes') || $request->notes == true;
        return RoomingReportRepository::viewReport($inventory->repository, 'accommodation-inventories.rooming.export', $notes, ['accommodation' => $accommodation, 'accommodationInventory' => $inventory,]);
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

    public function update(Request $request, Accommodation $accommodation, AccommodationInventory $inventory)
    {
        $request->validate(AccommodationInventory::getValidationRules());
        $inventory->update([
            'room_type_id' => $request->input('room_type_id'),
            'board_type_id' => $request->input('board_type_id'),
            'stock_parent_id' => $request->input('stock_parent_id'),
            'check_in' => $request->input('check_in'),
            'check_in_time_confirmed' => $request->input('check_in_time_confirmed') == 'on' ? 1 : 0,
            'check_out' => $request->input('check_out'),
            'check_out_time_confirmed' => $request->input('check_out_time_confirmed') == 'on' ? 1 : 0,
            'fit_selectable' => $request->input('fit_selectable') == 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price') ?? 0,
            'sales_price' => $request->input('sales_price') ?? 0,
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
        ]);
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function destroy(Accommodation $accommodation, AccommodationInventory $inventory)
    {
        if ($inventory->tourComponents()->count() > 0) {
            return back()->withErrors(trans('custom.used-in-tour', ['model' => 'Accommodation Inventory']));
        }
        $inventory->delete();
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function duplicate(Accommodation $accommodation, AccommodationInventory $inventory)
    {
        $inventory = $inventory->replicate();
        $inventory->save();
        return redirect()->route('accommodation-inventories.edit', ['accommodation' => $accommodation, 'inventory' => $inventory,]);
    }
}
