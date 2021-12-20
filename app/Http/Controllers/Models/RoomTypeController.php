<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{

    public function index()
    {
        return view('pages.models.room_types.table', ['roomTypes' => RoomType::all(),]);
    }

    public function create()
    {
        return view('pages.models.room_types.create');
    }

    public function store(Request $request)
    {
        $request->validate(RoomType::getValidationRules());
        $roomType = RoomType::create([
            'name' => $request->input('name'),
            'maximum_occupancy' => $request->input('maximum_occupancy'),
        ]);
        return redirect()->route('room-types.view', ['roomType' => $roomType,]);
    }

    public function view(RoomType $roomType)
    {
        return view('pages.models.room_types.view', ['roomType' => $roomType,]);
    }

    public function edit(RoomType $roomType)
    {
        return view('pages.models.room_types.update', ['roomType' => $roomType,]);
    }

    public function update(Request $request, RoomType $roomType)
    {
        $request->validate(RoomType::getValidationRules());
        $roomType->update([
            'name' => $request->input('name'),
            'maximum_occupancy' => $request->input('maximum_occupancy'),
        ]);
        return redirect()->route('room-types.view', ['roomType' => $roomType,]);
    }

    public function destroy(RoomType $roomType)
    {
        $roomType->delete();
        return redirect()->route('room-types.all');
    }
}
