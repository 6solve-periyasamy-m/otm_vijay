<?php

namespace App\Http\Controllers\Admin\Merchandise;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Merchandise\MerchandiseInventoryRequest;
use App\Models\Merchandise\Merchandise;
use App\Models\Merchandise\MerchandiseInventory;

class MerchandiseInventoryController extends Controller
{
    public function create(Merchandise $merchandise, string $view = 'overview')
    {
        return view('pages.admin.merchandise.inventory.form', ['merchandise' => $merchandise, 'view' => $view,]);
    }

    public function store(MerchandiseInventoryRequest $request, Merchandise $merchandise, string $view = 'overview')
    {
        $merchandise->repository->createInventory($request->getDataset(), $request->image);
        return redirect()->route('merchandise.view', ['merchandise' => $merchandise, 'view' => $view,]);
    }

    public function edit(Merchandise $merchandise, MerchandiseInventory $inventory, string $view = 'overview')
    {
        return view('pages.admin.merchandise.inventory.form', ['merchandise' => $merchandise, 'inventory' => $inventory, 'view' => $view,]);
    }

    public function duplicate(Merchandise $merchandise, MerchandiseInventory $inventory, string $view = 'overview')
    {
        $clone = $inventory->replicate(['id']);
        $clone->save();
        return view('pages.admin.merchandise.inventory.form', ['merchandise' => $merchandise, 'inventory' => $clone, 'view' => $view,]);
    }

    public function update(MerchandiseInventoryRequest $request, Merchandise $merchandise, MerchandiseInventory $inventory, string $view = 'overview')
    {
        $inventory->repository->updateWithImage($request->getDataset(), $request->image);
        if ($view === 'detailed') {
            return redirect()->route('merchandise.detailed', ['merchandise' => $merchandise,]);
        }
        return redirect()->route('merchandise.view', ['merchandise' => $merchandise,]);
    }

    public function delete(Merchandise $merchandise, MerchandiseInventory $inventory, string $view = 'overview')
    {
        if (!$inventory->repository->delete()) {
            return back()->withErrors(['msg' => 'Component is used on tours']);
        }
        if ($view === 'detailed') {
            return redirect()->route('merchandise.detailed', ['merchandise' => $merchandise,]);
        }
        return redirect()->route('merchandise.view', ['merchandise' => $merchandise,]);
    }
}
