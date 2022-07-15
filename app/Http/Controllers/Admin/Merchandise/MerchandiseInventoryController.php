<?php

namespace App\Http\Controllers\Admin\Merchandise;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Merchandise\MerchandiseInventoryRequest;
use App\Models\Merchandise\Merchandise;
use App\Models\Merchandise\MerchandiseInventory;

class MerchandiseInventoryController extends Controller
{
    public function create(Merchandise $merchandise)
    {
        return view('pages.admin.merchandise.inventory.form', ['merchandise' => $merchandise,]);
    }

    public function store(MerchandiseInventoryRequest $request, Merchandise $merchandise)
    {
        $merchandise->repository->createInventory($request->getDataset(), $request->image);
        return redirect()->route('merchandise.view', ['merchandise' => $merchandise,]);
    }

    public function edit(Merchandise $merchandise, MerchandiseInventory $inventory)
    {
        return view('pages.admin.merchandise.inventory.form', ['merchandise' => $merchandise, 'inventory' => $inventory,]);
    }

    public function update(MerchandiseInventoryRequest $request, Merchandise $merchandise, MerchandiseInventory $inventory)
    {
        $inventory->repository->updateWithImage($request->getDataset(), $request->image);
        return redirect()->route('merchandise.view', ['merchandise' => $merchandise,]);
    }

    public function delete(Merchandise $merchandise, MerchandiseInventory $inventory)
    {

    }
}
