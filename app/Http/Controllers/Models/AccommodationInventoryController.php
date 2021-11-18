<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\AccommodationInventory;
use Illuminate\Http\Request;

class AccommodationInventoryController extends Controller
{

    public function index()
    {
        return view('pages.models.accommodation_inventories.table', ['accommodationInventories' => AccommodationInventory::all(),]);
    }

    public function create(Accommodation $accommodation)
    {
        return view('pages.models.accommodation_inventories.create', ['accommodation' => $accommodation, ]);
    }

    public function store(Request $request, Accommodation $accommodation)
    {
        $request->validate(AccommodationInventory::RULES);
        $accommodationInventory = AccommodationInventory::make([
            'room_type_id' => $request->input('room_type_id'),
            'board_type_id' => $request->input('board_type_id'),
            'check_in' => $request->input('check_in'),
            'check_in_time_confirmed' => $request->input('check_in_time_confirmed') == 'on' ? 1 : 0,
            'check_out' => $request->input('check_out'),
            'check_out_time_confirmed' => $request->input('check_out_time_confirmed') == 'on' ? 1 : 0,
            'fit_selectable' => $request->input('fit_selectable') == 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'sales_price' => $request->input('sales_price'),
            'notes' => $request->input('notes'),
        ]);
        $accommodation->inventory()->save($accommodationInventory);
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function view(Accommodation $accommodation, AccommodationInventory $accommodationInventory)
    {
        return view('pages.models.accommodation_inventories.view', ['accommodationInventory' => $accommodationInventory,]);
    }

    public function edit(Accommodation $accommodation, AccommodationInventory $accommodationInventory)
    {
        return view('pages.models.accommodation_inventories.update', ['accommodation' => $accommodation, 'accommodationInventory' => $accommodationInventory,]);
    }

    public function update(Request $request, Accommodation $accommodation, AccommodationInventory $accommodationInventory)
    {
        $request->validate(AccommodationInventory::RULES);
        $accommodationInventory->update([
            'room_type_id' => $request->input('room_type_id'),
            'board_type_id' => $request->input('board_type_id'),
            'check_in' => $request->input('check_in'),
            'check_in_time_confirmed' => $request->input('check_in_time_confirmed') == 'on' ? 1 : 0,
            'check_out' => $request->input('check_out'),
            'check_out_time_confirmed' => $request->input('check_out_time_confirmed') == 'on' ? 1 : 0,
            'fit_selectable' => $request->input('fit_selectable') == 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'sales_price' => $request->input('sales_price'),
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }

    public function destroy(Accommodation $accommodation, AccommodationInventory $accommodationInventory)
    {
        $accommodationInventory->delete();
        return redirect()->route('accommodations.view', ['accommodation' => $accommodation,]);
    }
}
