<?php

namespace App\Http\Controllers\Admin\Accommodation;

use App\Http\Controllers\Controller;
use App\Models\Accommodation\BoardType;
use Illuminate\Http\Request;

class BoardTypeController extends Controller
{
    public function create()
    {
        return view('pages.admin.accommodation.inventory.board.form');
    }

    public function store(Request $request)
    {
        $request->validate(BoardType::getValidationRules());
        $boardType = BoardType::create([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function edit(BoardType $boardType)
    {
        return view('pages.admin.accommodation.inventory.board.form', ['boardType' => $boardType,]);
    }

    public function update(Request $request, BoardType $boardType)
    {
        $request->validate(BoardType::getValidationRules($boardType->id));
        $boardType->update([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function destroy(BoardType $boardType)
    {
        $repo = $boardType->repository;
        $repo->delete();
        return $repo->getReturnURL();
    }
}
