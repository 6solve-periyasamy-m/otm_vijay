<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MerchandiseController extends Controller
{

    public function index(Tour $tour)
    {
        return view('pages.models.merchandise.table', ['merchandises' => Merchandise::all(), 'tour' => $tour,]);
    }

    public function create(Tour $tour)
    {
        return view('pages.models.merchandise.create', ['tour' => $tour,]);
    }

    public function store(Request $request, Tour $tour)
    {
        $request->validate(Merchandise::getValidationRules());
        $merchandise = Merchandise::make([
            'name' => $request->input('name'),
            'tour_component_type' => $request->input('tour_component_type'),
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'tour_sales_price' => $request->input('sales_price'),
            'notes' => $request->input('notes'),
        ]);
        if ($request->has('image') && $request->file('image') != null) {
            $merchandise->image_url = $request->file('image')->storePublicly('uploads/images');
        }
        $tour->merchandise()->save($merchandise);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function view(Tour $tour, Merchandise $merchandise)
    {
        return view('pages.models.merchandise.view', ['merchandise' => $merchandise, 'tour' => $tour,]);
    }

    public function edit(Tour $tour, Merchandise $merchandise)
    {
        return view('pages.models.merchandise.update', ['merchandise' => $merchandise, 'tour' => $tour,]);
    }

    public function update(Request $request, Tour $tour, Merchandise $merchandise)
    {
        $request->validate(Merchandise::getValidationRules());
        $merchandise->update([
            'name' => $request->input('name'),
            'tour_component_type' => $request->input('tour_component_type'),
            'stock' => $request->input('stock'),
            'purchase_price' => $request->input('purchase_price'),
            'tour_sales_price' => $request->input('sales_price'),
            'notes' => $request->input('notes'),
        ]);
        if ($request->has('image') && $request->file('image') != null) {
            if (isset($merchandise->image_url)) {
                File::delete(public_path($merchandise->image_url));
            }
            $merchandise->image_url = $request->file('image')->storePublicly('uploads/images');
        }
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour, Merchandise $merchandise)
    {
        $merchandise->delete();
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }
}
