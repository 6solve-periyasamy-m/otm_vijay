<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    public function view(Booking $booking)
    {
        return view('pages.admin.booking.view', ['booking' => $booking]);
    }

    public function convert(Booking $booking): RedirectResponse
    {
        $lead = $booking->leadTraveller;
        $lead->first_name = $lead->first_name ?? "Unset";
        $lead->last_name = $lead->last_name ?? "Unset";
        $lead->save();
        $order = $booking->repository->convertToOrder();
        return redirect()->route('orders.view', ['order' => $order]);
    }
}
