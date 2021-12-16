<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Repository\TourRepository;
use Illuminate\Http\Request;

class TourController extends Controller
{

    public function index()
    {
        return view('pages.models.tours.table', ['tours' => Tour::all(),]);
    }

    public function create()
    {
        return view('pages.models.tours.create');
    }

    public function store(Request $request)
    {
        $request->validate(Tour::getValidationRules());
        $tour = Tour::create([
            'event_id' => $request->input('event_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'base_price_per_person' => $request->input('base_price_per_person'),
            'margin' => $request->input('margin'),
            'single_occupancy_surcharge' => $request->input('single_occupancy_surcharge'),
            'stock_control_active' => $request->input('stock_control_active') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'booking_form_url' => $request->input('booking_form_url'),
            'tour_category_id' => $request->input('tour_category_id'),
            'deposit' => $request->input('deposit'),
            'is_active' => $request->input('is_active') === 'on' ? 1 : 0,
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function view(Tour $tour)
    {
        return view('pages.tour.view', TourRepository::getTourDetails($tour->id));
    }

    public function edit(Tour $tour)
    {
        return view('pages.models.tours.update', ['tour' => $tour,]);
    }

    public function update(Request $request, Tour $tour)
    {
        $request->validate(Tour::getValidationRules());
        $tour->update([
            'event_id' => $request->input('event_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'base_price_per_person' => $request->input('base_price_per_person'),
            'margin' => $request->input('margin'),
            'deposit' => $request->input('deposit'),
            'single_occupancy_surcharge' => $request->input('single_occupancy_surcharge'),
            'stock_control_active' => $request->input('stock_control_active') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'booking_form_url' => $request->input('booking_form_url'),
            'tour_category_id' => $request->input('tour_category_id'),
            'is_active' => $request->input('is_active') === 'on' ? 1 : 0,
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour)
    {
        if ($tour->orders()->count() > 0) {
            return back()->withErrors(trans('custom.used-elsewhere', ['model' => 'Tour', 'parent' => 'ORder']));
        }
        $tour->delete();
        return redirect()->route('tours.all');
    }
}
