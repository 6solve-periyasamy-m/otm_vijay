<?php

namespace App\Listeners;

use App\Events\Order\OrderCreatedEvent;
use App\Events\Order\Payment\PaymentCreatedEvent;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentIntention;
use App\Models\PaymentMethod;
use App\Repository\BookingRepository;
use App\Repository\CustomerBookingRepository;
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
            if (!isset($intention)) return;
            if (!$intention->processed) {
                $order = OrderRepository::getOrderFromBookingReference($intention->reference);
                if (isset($order)) {
                    $payment = $intention->makePayment($data['amount'] / 100, PaymentMethod::firstOrCreate('Stripe'), $payload['created']);
                    $order->payments()->save($payment);
                    $intention->process();
                    $intention->processed = true;
                    $intention->save();
                    event(new PaymentCreatedEvent($payment));
                    return;
                }
                $booking = Booking::where('token', $intention->reference)->first();
                if (isset($booking)) {
                    if (strlen($booking->token) == 64) {
                        $order = CustomerBookingRepository::convertBookingToOrder($booking);
                    } else {
                        $order = BookingRepository::convertBookingToOrder($booking);
                    }
                    $payment = $intention->makePayment($data['amount'] / 100, PaymentMethod::firstOrCreate('Stripe'), $payload['created']);
                    $order->payments()->save($payment);
                    $intention->processed = true;
                    $intention->save();
                    event(new OrderCreatedEvent($order));
                }
            }
        }
    }
}
