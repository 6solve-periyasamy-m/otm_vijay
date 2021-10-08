<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Tour;
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
        $request->validate(Tour::RULES);
        $tour = Tour::create([
            'event_id' => $request->input('event_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'base_price_per_person' => $request->input('base_price_per_person'),
            'margin' => $request->input('margin'),
            'single_occupancy_surcharge' => $request->input('single_occupancy_surcharge'),
            'stock_control_active' => $request->input('stock_control_active') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'booking_form_url' => $request->input('booking_form_url'),
            'tour_colour_id' => $request->input('tour_colour_id'),
            'is_active' => $request->input('is_active'),
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function view(Tour $tour)
    {
        return view('pages.models.tours.view', ['tour' => $tour,]);
    }

    public function edit(Tour $tour)
    {
        return view('pages.models.tours.update', ['tour' => $tour,]);
    }

    public function update(Request $request, Tour $tour)
    {
        $request->validate(Tour::RULES);
        $tour->update([
            'event_id' => $request->input('event_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'base_price_per_person' => $request->input('base_price_per_person'),
            'margin' => $request->input('margin'),
            'single_occupancy_surcharge' => $request->input('single_occupancy_surcharge'),
            'stock_control_active' => $request->input('stock_control_active') === 'on' ? 1 : 0,
            'stock' => $request->input('stock'),
            'booking_form_url' => $request->input('booking_form_url'),
            'tour_colour_id' => $request->input('tour_colour_id'),
            'is_active' => $request->input('is_active'),
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour)
    {
        $tour->delete();
        return redirect()->route('tours.all');
    }
}
