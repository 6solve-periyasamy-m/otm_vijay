<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\HatSize;
use Illuminate\Http\Request;

class HatSizeController extends Controller
{

    public function index()
    {
        return view('pages.admin.customer.hat-size.table', ['hatSizes' => HatSize::all(),]);
    }

    public function create()
    {
        return view('pages.admin.customer.hat-size.create');
    }

    public function store(Request $request)
    {
        $request->validate(HatSize::getValidationRules());
        $hatSize = HatSize::create([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function view(HatSize $hatSize)
    {
        return view('pages.admin.customer.hat-size.view', ['hatSize' => $hatSize,]);
    }

    public function edit(HatSize $hatSize)
    {
        return view('pages.admin.customer.hat-size.update', ['hatSize' => $hatSize,]);
    }

    public function update(Request $request, HatSize $hatSize)
    {
        $request->validate(HatSize::getValidationRules($hatSize->id));
        $hatSize->update([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function destroy(HatSize $hatSize)
    {
        $repo = $hatSize->repository;
        $repo->delete();
        return $repo->getReturnURL();
    }
}
