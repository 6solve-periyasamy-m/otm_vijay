<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TableRequest;
use App\Models\Tour\Tour;
use App\Repository\Model\Order\AtolRepository;
use App\Repository\Reporting\RoomingReportRepository;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(TableRequest $request)
    {
        if (($request->historic ?? (setting('system.historic', 6) < 0))) {
            $tours = Tour::all();
        } else {
            $tours = Tour::whereDate('date_to', '>', now()->subMonths(setting('system.historic', 6)))->get();
        }
        return view('pages.admin.tour.table', ['tours' => $tours, 'historic' => ($request->historic ?? false)]);
    }

    public function create()
    {
        return view('pages.admin.tour.form');
    }

    public function store(Request $request)
    {
        $request->validate(Tour::getValidationRules());
        $tour = Tour::create([
            'event_id' => $request->input('event_id'),
            'tax_bracket_id' => $request->input('tax_bracket_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'brand_id' => $request->input('brand_id') == 0 ? null : $request->input('brand_id'),
            'base_price_per_person' => $request->input('base_price_per_person'),
            'margin' => $request->input('margin'),
            'single_occupancy_surcharge' => $request->input('single_occupancy_surcharge'),
            'stock_control_active' => $request->input('stock_control_active') === 'on' ? 1 : 0,
            'atol_protected' => $request->input('atol_protected') == -1 ? null : $request->input('atol_protected'),
            'stock' => $request->input('stock'),
            'booking_form_url' => $request->input('booking_form_url'),
            'tour_category_id' => $request->input('tour_category_id'),
            'deposit' => $request->input('deposit'),
            'booking_fee' => $request->input('booking_fee') ?? 0,
            'is_active' => $request->input('is_active') === 'on' ? 1 : 0,
            'notes' => $request->input('notes'),
            'invoice_footer' => $request->input('invoice_footer'),
            'final_payment' => $request->input('final_payment'),
            'accommodation_stock_control' => $request->input('accommodation_stock_control') === 'on' ? 1 : 0,
            'activity_stock_control' => $request->input('activity_stock_control') === 'on' ? 1 : 0,
            'flight_stock_control' => $request->input('flight_stock_control') === 'on' ? 1 : 0,
            'transport_stock_control' => $request->input('transport_stock_control') === 'on' ? 1 : 0,
            'merchandise_stock_control' => $request->input('merchandise_stock_control') === 'on' ? 1 : 0,
            'terms' => $request->input('terms'),
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function fulfil(Tour $tour)
    {
        $tour->repository->fulfilAll();
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function view($tour)
    {
        $tour = Tour::with(
            'accommodationInventoryTours', 'accommodationInventoryTours.inventory','accommodationInventoryTours.inventory.roomType','accommodationInventoryTours.inventory.boardType', 'accommodationInventoryTours.inventory.component',
            'activityInventoryTours', 'activityInventoryTours.inventory','activityInventoryTours.inventory.ticketType', 'activityInventoryTours.inventory.component', 'activityInventoryTours.inventory.component.activityType',
            'flightInventoryTours', 'flightInventoryTours.inventory', 'flightInventoryTours.inventory.component', 'flightInventoryTours.inventory.component.airline', 'flightInventoryTours.inventory.component.departureAirport', 'transportInventoryTours.inventory.component.arrivalAddress',
            'transportInventoryTours', 'transportInventoryTours.inventory', 'transportInventoryTours.inventory.travelClass', 'transportInventoryTours.inventory.component', 'transportInventoryTours.inventory.component.operator', 'transportInventoryTours.inventory.component.departureAddress', 'transportInventoryTours.inventory.component.arrivalAddress',
            'merchandise', 'merchandise.inventory', 'merchandise.inventory.size', 'merchandise.inventory.variant', 'merchandise.inventory.component', 'merchandise.inventory.component.type',
            'paymentInstallments', 'orders', 'orders.leadBooker'
        )->find($tour);
        if (!isset($tour)) abort(404);
        return view('pages.admin.tour.view', ['tour' => $tour,]);
    }

    public function costing(Tour $tour)
    {
        return view('pages.admin.tour.costing', ['tour' => $tour,]);
    }

    public function duplicate(Tour $tour)
    {
        $newTour = $tour->clone();
        return redirect()->route('tours.edit', ['tour' => $newTour,]);
    }

    public function exportAtol(Tour $tour) {
        $asset = AtolRepository::generateAllAtolCertificates($tour->orders, $tour->name);
        if (!isset($asset)) {
            return back()->withErrors(['msg' => 'Something failed whilst trying to set this up, please try again later']);
        }
        return redirect($asset);
    }

    public function edit(Tour $tour)
    {
        return view('pages.admin.tour.form', ['tour' => $tour,]);
    }

    public function update(Request $request, Tour $tour)
    {
        $request->validate(Tour::getValidationRules());
        $tour->update([
            'event_id' => $request->input('event_id'),
            'tax_bracket_id' => $request->input('tax_bracket_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'brand_id' => $request->input('brand_id') == 0 ? null : $request->input('brand_id'),
            'base_price_per_person' => $request->input('base_price_per_person'),
            'margin' => $request->input('margin'),
            'deposit' => $request->input('deposit'),
            'booking_fee' => $request->input('booking_fee') ?? 0,
            'single_occupancy_surcharge' => $request->input('single_occupancy_surcharge'),
            'stock_control_active' => $request->input('stock_control_active') === 'on' ? 1 : 0,
            'atol_protected' => $request->input('atol_protected') == -1 ? null : $request->input('atol_protected'),
            'stock' => $request->input('stock'),
            'booking_form_url' => $request->input('booking_form_url'),
            'tour_category_id' => $request->input('tour_category_id'),
            'is_active' => $request->input('is_active') === 'on' ? 1 : 0,
            'notes' => $request->input('notes'),
            'invoice_footer' => $request->input('invoice_footer'),
            'accommodation_stock_control' => $request->input('accommodation_stock_control') === 'on' ? 1 : 0,
            'activity_stock_control' => $request->input('activity_stock_control') === 'on' ? 1 : 0,
            'flight_stock_control' => $request->input('flight_stock_control') === 'on' ? 1 : 0,
            'transport_stock_control' => $request->input('transport_stock_control') === 'on' ? 1 : 0,
            'merchandise_stock_control' => $request->input('merchandise_stock_control') === 'on' ? 1 : 0,
            'terms' => $request->input('terms'),
            'final_payment' => $request->input('final_payment'),
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour)
    {
        if ($tour->orders()->count() > 0) {
            return back()->withErrors(trans('custom.used-elsewhere', ['model' => 'Tour', 'parent' => 'Order']));
        }
        $tour->delete();
        return redirect()->route('tours.all');
    }

    public function restore($tour)
    {
        $tour = Tour::withTrashed()->findOrFail($tour);
        $tour->restore();
        return redirect()->route('tours.all');
    }
}
