<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\TShirtSize;
use Illuminate\Http\Request;

class TShirtSizeController extends Controller
{

    public function index()
    {
        return view('pages.models.t_shirt_sizes.table', ['tShirtSizes' => TShirtSize::all(),]);
    }

    public function create()
    {
        return view('pages.models.t_shirt_sizes.create');
    }

    public function store(Request $request)
    {
        $request->validate(TShirtSize::getValidationRules());
        $tShirtSize = TShirtSize::create([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function view(TShirtSize $tShirtSize)
    {
        return view('pages.models.t_shirt_sizes.view', ['tShirtSize' => $tShirtSize,]);
    }

    public function edit(TShirtSize $tShirtSize)
    {
        return view('pages.models.t_shirt_sizes.update', ['tShirtSize' => $tShirtSize,]);
    }

    public function update(Request $request, TShirtSize $tShirtSize)
    {
        $request->validate(TShirtSize::getValidationRules($tShirtSize->id));
        $tShirtSize->update([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function destroy(TShirtSize $tShirtSize)
    {
        $repo = $tShirtSize->repository;
        $repo->delete();
        return $repo->getReturnURL();
    }
}
