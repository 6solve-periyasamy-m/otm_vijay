<?php

namespace App\Http\Controllers\Admin\Merchandise;

use App\Http\Requests\Admin\Merchandise\MerchandiseInventoryTourRequest;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Tour\Tour;

class MerchandiseInventoryTourController
{
    public function edit(Tour $tour, MerchandiseInventoryTour $inventoryTour)
    {
        return view('pages.admin.merchandise.inventory.tour.form', ['tour' => $tour, 'inventoryTour' => $inventoryTour]);
    }

    public function update(MerchandiseInventoryTourRequest $request, Tour $tour, MerchandiseInventoryTour $inventoryTour)
    {
        $inventoryTour->repository->update($request->getDataset());
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function delete(Tour $tour, MerchandiseInventoryTour $inventoryTour)
    {
        $deleted = $inventoryTour->delete();
        if (!$deleted) {
            return back()->withErrors(['msg' => 'Could not successfully delete the component, as it has dependants']);
        }
        return redirect()->route('tours.view', ['tour' => $tour,])->with('success', 'Component Deleted Successfully');
    }

    public function restore(Tour $tour, $inventoryTour)
    {
        $inventoryTour = MerchandiseInventoryTour::withTrashed()->find($inventoryTour);
        if (!isset($inventoryTour)) abort(404);
        $inventoryTour->repository->update(['is_bookable' => true, 'deleted_at' => null,]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }
}
