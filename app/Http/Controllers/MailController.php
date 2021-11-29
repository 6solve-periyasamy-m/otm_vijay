<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Models\Order;
use App\Repository\MailRepository;
use App\Repository\OrderRepository;
use App\Repository\SettingsRepository;
use App\Repository\ShortCodeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function editBooking() {
        return view('pages.email.editor', [
            'body' => MailRepository::getEmailTemplate('booking.confirmation'),
            'codes' => ShortCodeRepository::getOrderShortCodes(),
            'action' => route('email.booking.update'),
            'demo' => route('email.booking.demo'),
            'templateName' => 'Booking Confirmation'
        ]);
    }

    public function storeBooking(Request $request) {
        SettingsRepository::set('booking.confirmation', $request->input('body'));
        return redirect()->route('email.booking.edit');
    }

    public function demoBooking() {
        Mail::to(Auth::user())->send(new BookingConfirmation(null));
        return redirect()->route('email.booking.edit');
    }

    public function demoOrderBooking(Order $order) {
        Mail::to($order->leadBooker->customer->email_address)->send(new BookingConfirmation($order));
        return redirect()->route('email.booking.edit');
    }
}
