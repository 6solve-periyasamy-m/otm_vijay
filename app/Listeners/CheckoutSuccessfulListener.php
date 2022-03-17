<?php

namespace App\Listeners;

use App\Events\Order\OrderCreatedEvent;
use App\Events\Order\Payment\PaymentCreatedEvent;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentIntention;
use App\Models\PaymentMethod;
use App\Repository\BookingRepository;
use App\Repository\OrderRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\WebhookClient\Models\WebhookCall;

class CheckoutSuccessfulListener implements ShouldQueue
{
    public function handle(WebhookCall $call)
    {
        $payload = $call->payload;
        $data = $payload['data']['object'];
        $metadata = $data['metadata'];
        if (key_exists('intention_id', $metadata)) {
            $intention = PaymentIntention::fetch($metadata['intention_id']);
            if (isset($intention) && !$intention->processed) {
                $order = OrderRepository::getOrderFromBookingReference($intention->reference);
                if (isset($order)) {
                    $payment = $intention->makePayment($data['amount'] / 100, PaymentMethod::firstOrCreate('Stripe'), $payload['created']);
                    $order->payments()->save($payment);
                    $intention->processed = true;
                    $intention->save();
                    event(new PaymentCreatedEvent($payment));
                }
            }
            $booking = Booking::where('token', $metadata['booking_reference'])->first();
            if (isset($booking)) {
                $order = BookingRepository::convertBookingToOrder($booking);
                event(new OrderCreatedEvent($order));
                $payment = Payment::make([
                    'payment_method_id' => PaymentMethod::firstOrCreate('Stripe')->id,
                    'paid_on' => Carbon::parse($payload['created']),
                    'customer_id' => $metadata['customer_id'],
                    'amount' => $data['amount_total'] / 100,
                    'payment_type' => $metadata['payment_type'],
                ]);
                $order->payments()->save($payment);
                event(new PaymentCreatedEvent($payment));

            }
    }
}
