<?php

namespace App\Http\Controllers\Admin\Merchandise;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SimpleModelRequest;
use App\Models\Merchandise\Variant;

class VariantController extends Controller
{
    public function create()
    {
        return view('pages.admin.merchandise.variants.form');
    }

    public function store(SimpleModelRequest $request)
    {
        Variant::create(['name' => $request->name,]);
        return view('pages.close');
    }

    public function edit(Variant $variant)
    {
        return view('pages.admin.merchandise.variants.form', ['variant' => $variant,]);
    }

    public function update(SimpleModelRequest $request, Variant $variant)
    {
        $variant->update(['name' => $request->name,]);
        $variant->save();
        return view('pages.close');
    }

    public function delete(Variant $variant)
    {
        $deleted = $variant->repository->delete();
        if (!$deleted) {
            return back()->withErrors(['msg' => 'Cannot delete a type that is in-use',]);
        }
        return back();
    }
}
