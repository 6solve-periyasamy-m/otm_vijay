<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\BoardType;
use Illuminate\Http\Request;

class BoardTypeController extends Controller
{

    public function index()
    {
        return view('pages.models.board_types.table', ['boardTypes' => BoardType::all(),]);
    }

    public function create()
    {
        return view('pages.models.board_types.create');
    }

    public function store(Request $request)
    {
        $request->validate(BoardType::getValidationRules());
        $boardType = BoardType::create([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('board-types.view', ['boardType' => $boardType,]);
    }

    public function view(BoardType $boardType)
    {
        return view('pages.models.board_types.view', ['boardType' => $boardType,]);
    }

    public function edit(BoardType $boardType)
    {
        return view('pages.models.board_types.update', ['boardType' => $boardType,]);
    }

    public function update(Request $request, BoardType $boardType)
    {
        $request->validate(BoardType::getValidationRules());
        $boardType->update([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('board-types.view', ['boardType' => $boardType,]);
    }

    public function destroy(BoardType $boardType)
    {
        $boardType->delete();
        return redirect()->route('board-types.all');
    }
}
