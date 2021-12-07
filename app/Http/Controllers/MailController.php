<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmationMailable;
use App\Mail\PaymentDueMailable;
use App\Mail\PaymentMadeMailable;
use App\Mail\RefundGivenMailable;
use App\Models\Order;
use App\Models\Payment;
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
            'body' => MailRepository::getEmailTemplate('email.booking.confirmation'),
            'codes' => ShortCodeRepository::getOrderShortCodes(),
            'action' => route('email.booking.update'),
            'demo' => route('email.booking.demo'),
            'templateName' => 'Booking Confirmation'
        ]);
    }

    public function storeBooking(Request $request) {
        SettingsRepository::set('email.booking.confirmation', $request->input('body'));
        return redirect()->route('email.booking.edit');
    }

    public function demoBooking() {
        Mail::to(Auth::user())->send(new BookingConfirmationMailable(null));
        return redirect()->route('email.booking.edit');
    }

    public function demoOrderBooking(Order $order) {
        Mail::to($order->leadBooker->customer->email_address)->send(new BookingConfirmationMailable($order));
        return redirect()->route('email.booking.edit');
    }

    public function editPaymentDue() {
        return view('pages.email.editor', [
            'body' => MailRepository::getEmailTemplate('email.payment.due'),
            'codes' => ShortCodeRepository::getOrderShortCodes(),
            'action' => route('email.payment-due.update'),
            'demo' => route('email.payment-due.demo'),
            'templateName' => 'Payment Due'
        ]);
    }

    public function storePaymentDue(Request $request) {
        SettingsRepository::set('email.payment.due', $request->input('body'));
        return redirect()->route('email.payment-due.edit');
    }

    public function demoPaymentDue() {
        Mail::to(Auth::user())->send(new PaymentDueMailable(null));
        return redirect()->route('email.payment-due.edit');
    }

    public function demoOrderPaymentDue(Order $order) {
        Mail::to($order->leadBooker->customer->email_address)->send(new PaymentDueMailable($order));
        return redirect()->route('email.payment-due.edit');
    }

    public function editPaymentMade() {
        return view('pages.email.editor', [
            'body' => MailRepository::getEmailTemplate('email.payment.made'),
            'codes' => ShortCodeRepository::getPaymentShortCodes(),
            'action' => route('email.payment-made.update'),
            'demo' => route('email.payment-made.demo'),
            'templateName' => 'Payment Made'
        ]);
    }

    public function storePaymentMade(Request $request) {
        SettingsRepository::set('email.payment.made', $request->input('body'));
        return redirect()->route('email.payment-made.edit');
    }

    public function demoPaymentMade() {
        Mail::to(Auth::user())->send(new PaymentMadeMailable(null));
        return redirect()->route('email.payment-made.edit');
    }

    public function demoOrderPaymentMade(Payment $payment) {
        Mail::to($payment->order->leadBooker->customer->email_address)->send(new PaymentMadeMailable($payment));
        return redirect()->route('email.payment-made.edit');
    }

    public function editRefundGiven() {
        return view('pages.email.editor', [
            'body' => MailRepository::getEmailTemplate('email.refund.given'),
            'codes' => ShortCodeRepository::getPaymentShortCodes(),
            'action' => route('email.refund-given.update'),
            'demo' => route('email.refund-given.demo'),
            'templateName' => 'Refund Given'
        ]);
    }

    public function storeRefundGiven(Request $request) {
        SettingsRepository::set('email.refund.given', $request->input('body'));
        return redirect()->route('email.refund-given.edit');
    }

    public function demoRefundGiven() {
        Mail::to(Auth::user())->send(new RefundGivenMailable(null));
        return redirect()->route('email.refund-given.edit');
    }

    public function demoOrderRefundGiven(Payment $payment) {
        Mail::to($payment->order->leadBooker->customer->email_address)->send(new RefundGivenMailable($payment));
        return redirect()->route('email.refund-given.edit');
    }
}
