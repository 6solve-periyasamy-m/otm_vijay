<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\Request;

class OperatorController extends Controller
{

    public function index()
    {
        return view('pages.models.operators.table', ['operators' => Operator::all(),]);
    }

    public function create()
    {
        return view('pages.models.operators.create');
    }

    public function store(Request $request)
    {
        $request->validate(Operator::getValidationRules());
        $operator = Operator::create([
            'name' => $request->input('name'),
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('operators.view', ['operator' => $operator,]);
    }

    public function view(Operator $operator)
    {
        return view('pages.models.operators.view', ['operator' => $operator,]);
    }

    public function edit(Operator $operator)
    {
        return view('pages.models.operators.update', ['operator' => $operator,]);
    }

    public function update(Request $request, Operator $operator)
    {
        $request->validate(Operator::getValidationRules());
        $operator->update([
            'name' => $request->input('name'),
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('operators.view', ['operator' => $operator,]);
    }

    public function destroy(Operator $operator)
    {
        $operator->delete();
        return redirect()->route('operators.all');
    }
}
