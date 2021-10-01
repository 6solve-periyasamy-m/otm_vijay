<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\AccommodationInventory;
use Illuminate\Http\Request;

class AccommodationInventoryController extends Controller
{

    public function index()
    {
        return view('pages.models.accommodation_inventories.table', ['accommodationInventories' => AccommodationInventory::all(),]);
    }

    public function create()
    {
        return view('pages.models.accommodation_inventories.create');
    }

    public function store(Request $request)
    {
        $accommodationInventory = AccommodationInventory::create([
            'accommodation_id' => $request->input('accommodation_id'),
            'room_type_id' => $request->input('room_type_id'),
            'board_type_id' => $request->input('board_type_id'),
            'check_in_date_time' => $request->input('check_in_date_time'),
            'checkin_confirmed' => $request->input('checkin_confirmed'),
            'check_out_date_time' => $request->input('check_out_date_time'),
            'checkout_confirmed' => $request->input('checkout_confirmed'),
            'fit_selectable' => $request->input('fit_selectable'),
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'sales_price' => $request->input('sales_price'),
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('accommodation-inventories.view', ['accommodationInventory' => $accommodationInventory,]);
    }

    public function view(AccommodationInventory $accommodationInventory)
    {
        return view('pages.models.accommodation_inventories.view', ['accommodationInventory' => $accommodationInventory,]);
    }

    public function edit(AccommodationInventory $accommodationInventory)
    {
        return view('pages.models.accommodation_inventories.update', ['accommodationInventory' => $accommodationInventory,]);
    }

    public function update(Request $request, AccommodationInventory $accommodationInventory)
    {
        $accommodationInventory->update([
            'accommodation_id' => $request->input('accommodation_id'),
            'room_type_id' => $request->input('room_type_id'),
            'board_type_id' => $request->input('board_type_id'),
            'check_in_date_time' => $request->input('check_in_date_time'),
            'checkin_confirmed' => $request->input('checkin_confirmed'),
            'check_out_date_time' => $request->input('check_out_date_time'),
            'checkout_confirmed' => $request->input('checkout_confirmed'),
            'fit_selectable' => $request->input('fit_selectable'),
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'sales_price' => $request->input('sales_price'),
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('accommodation-inventories.view', ['accommodationInventory' => $accommodationInventory,]);
    }

    public function destroy(AccommodationInventory $accommodationInventory)
    {
        $accommodationInventory->delete();
        return redirect()->route('accommodation-inventories.all');
    }
}
