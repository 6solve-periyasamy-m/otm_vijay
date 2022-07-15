<?php

namespace App\Http\Controllers\Admin\Merchandise;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Merchandise\MerchandiseRequest;
use App\Models\Merchandise\Merchandise;

class MerchandiseController extends Controller
{
    public function index()
    {
        $merch = Merchandise::withCount('inventories')->get();
        return view('pages.admin.merchandise.table', ['merchandise' => $merch,]);
    }

    public function create()
    {
        return view('pages.admin.merchandise.create');
    }

    public function store(MerchandiseRequest $request)
    {
        if ($request->hasFile('image')) {
            $img = store_file($request->image);
        }
        $merch = Merchandise::create([
            'name' => $request->name,
            'merchandise_type_id' => $request->type,
            'image_url' => $img ?? null,
            'notes' => $request->notes,
        ]);
        return redirect()->route('merchandise.view', ['merchandise' => $merch,]);
    }

    public function show(Merchandise $merchandise)
    {

    }

    public function edit(Merchandise $merchandise)
    {

    }

    public function update(MerchandiseRequest $request, Merchandise $merchandise)
    {

    }

    public function destroy(Merchandise $merchandise)
    {

    }
}
