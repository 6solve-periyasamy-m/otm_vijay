<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Mail\PaymentDue;
use App\Mail\PaymentMade;
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

    public function editPaymentDue() {
        return view('pages.email.editor', [
            'body' => MailRepository::getEmailTemplate('payment.due'),
            'codes' => ShortCodeRepository::getOrderShortCodes(),
            'action' => route('email.payment-due.update'),
            'demo' => route('email.payment-due.demo'),
            'templateName' => 'Payment Due'
        ]);
    }

    public function storePaymentDue(Request $request) {
        SettingsRepository::set('payment.due', $request->input('body'));
        return redirect()->route('email.payment-due.edit');
    }

    public function demoPaymentDue() {
        Mail::to(Auth::user())->send(new PaymentDue(null));
        return redirect()->route('email.payment-due.edit');
    }

    public function demoOrderPaymentDue(Order $order) {
        Mail::to($order->leadBooker->customer->email_address)->send(new PaymentDue($order));
        return redirect()->route('email.payment-due.edit');
    }

    public function editPaymentMade() {
        return view('pages.email.editor', [
            'body' => MailRepository::getEmailTemplate('payment.due'),
            'codes' => ShortCodeRepository::getOrderShortCodes(),
            'action' => route('email.payment-made.update'),
            'demo' => route('email.payment-made.demo'),
            'templateName' => 'Payment Made'
        ]);
    }

    public function storePaymentMade(Request $request) {
        SettingsRepository::set('payment.due', $request->input('body'));
        return redirect()->route('email.payment-made.edit');
    }

    public function demoPaymentMade() {
        Mail::to(Auth::user())->send(new PaymentMade(null));
        return redirect()->route('email.payment-made.edit');
    }

    public function demoOrderPaymentMade(Order $order) {
        Mail::to($order->leadBooker->customer->email_address)->send(new PaymentMade($order));
        return redirect()->route('email.payment-made.edit');
    }
}
