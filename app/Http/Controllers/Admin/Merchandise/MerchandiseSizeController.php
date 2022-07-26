<?php

namespace App\Http\Controllers\Admin\Merchandise;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SimpleModelRequest;
use App\Models\Merchandise\MerchandiseSize;
use App\Models\Merchandise\MerchandiseType;

class MerchandiseSizeController extends Controller
{
    public function create()
    {
        return view('pages.admin.merchandise.sizes.form');
    }

    public function store(SimpleModelRequest $request)
    {
        MerchandiseSize::create(['name' => $request->name,]);
        return view('pages.close');
    }

    public function edit(MerchandiseSize $size)
    {
        return view('pages.admin.merchandise.sizes.form', ['size' => $size,]);
    }

    public function update(SimpleModelRequest $request, MerchandiseSize $size)
    {
        $size->update(['name' => $request->name,]);
        $size->save();
        return view('pages.close');
    }
}
