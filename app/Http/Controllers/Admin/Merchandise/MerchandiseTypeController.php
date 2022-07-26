<?php

namespace App\Http\Controllers\Admin\Merchandise;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SimpleModelRequest;
use App\Models\Merchandise\MerchandiseType;

class MerchandiseTypeController extends Controller
{
    public function create()
    {
        return view('pages.admin.merchandise.types.form');
    }

    public function store(SimpleModelRequest $request)
    {
        MerchandiseType::create(['name' => $request->name,]);
        return view('pages.close');
    }

    public function edit(MerchandiseType $type)
    {
        return view('pages.admin.merchandise.types.form', ['type' => $type,]);
    }

    public function update(SimpleModelRequest $request, MerchandiseType $type)
    {
        $type->update(['name' => $request->name,]);
        $type->save();
        return view('pages.close');
    }
}
