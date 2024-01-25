<?php

namespace App\Http\Controllers\Admin\Accommodation;

use App\Http\Controllers\Controller;
use App\Models\Accommodation\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
        public function create()
    {
        return view('pages.admin.accommodation.inventory.room.form');
    }

    public function store(Request $request)
    {
        $request->validate(RoomType::getValidationRules());
        $roomType = RoomType::create([
            'name' => $request->input('name'),
            'maximum_occupancy' => abs($request->input('maximum_occupancy')),
        ]);
        return view('pages.close');
    }

    public function edit(RoomType $roomType)
    {
        return view('pages.admin.accommodation.inventory.room.form', ['roomType' => $roomType,]);
    }

    public function update(Request $request, RoomType $roomType)
    {
        $request->validate(RoomType::getValidationRules($roomType->id));
        $roomType->update([
            'name' => $request->input('name'),
            'maximum_occupancy' => abs($request->input('maximum_occupancy')),
        ]);
        return view('pages.close');
    }

    public function destroy(RoomType $roomType)
    {
        $repo = $roomType->repository;
        $repo->delete();
        return $repo->getReturnURL();
    }
}
