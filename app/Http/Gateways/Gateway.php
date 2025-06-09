<?php

namespace App\Http\Gateways;

use App\Events\Order\OrderCreatedEvent;
use App\Events\Order\Payment\PaymentCreatedEvent;
use App\Exceptions\RemoteGatewayError;
use App\Exceptions\UnauthorizedGatewayException;
use App\Http\Gateways\Storage\LineItem;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Helper\Enum\NotificationType;
use App\Models\Location\Currency;
use App\Models\Order\Order;
use App\Models\Order\Payment\PaymentIntention;
use App\Models\Order\Payment\PaymentMethod;
use App\Repository\Model\Order\OrderRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

abstract class Gateway
{
    /**
     * @param LineItem[] $items
     * @param PaymentIntention $intention
     * @param Customer|BookingTraveller $customer
     * @param string|null $success The redirect URL for
     * @return string The URL for the checkout gateway
     * @throws UnauthorizedGatewayException
     * @throws RemoteGatewayError
     */
    abstract public function checkout(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string;

    abstract public function process(string $reference, float $amount, string $created = null, string|null $currency = null): void;

    public function processIntention(PaymentIntention $intention, float $amount, string $gateway, string $created = null, string|null $currency = null): ?Order
    {
        if (!$intention->processed) {
            $order = OrderRepository::getFromBookingReference($intention->reference);
            if (isset($order)) {
                $payment = $intention->makePayment($amount / 100, PaymentMethod::findOrCreate($gateway), $created ?? now(), Currency::whereCode($currency)->first());
                $order->payments()->save($payment);
                $intention->process();
                $intention->processed = true;
                $intention->save();
                if (empty($intention->data)) {
                    $order->createNotification(NotificationType::PAYMENT_MADE, 'Payment of ' . f_currency($amount / 100) . " made.", $intention->customer);
                } else {
                    $order->createNotification(NotificationType::COMPONENTS_CHANGED, 'Upgrade/Add-on purchased for order', $intention->customer);
                }
                event(new PaymentCreatedEvent($payment));
                return $order;
            }
            $booking = Booking::where('token', $intention->reference)->first();
            if (isset($booking)) {
                $order = $booking->repository->convertToOrder(now());
                $intention->customer_id = $order->leadBooker->customer_id;
                $intention->save();
                $payment = $intention->makePayment($amount / 100, PaymentMethod::findOrCreate($gateway), $created ?? now(), Currency::whereCode($currency)->first());
                $order->payments()->save($payment);
                $intention->processed = true;
                $intention->save();
                $order->createNotification(NotificationType::ORDER_CREATED, 'Booking confirmed', $intention->customer);
                event(new OrderCreatedEvent($order));
                return $order;
            }
        }
        return null;
    }

    public function success(Request $request): Factory|View|Application
    {
        return view('pages.payments.success');
    }

    public function cancelled(Request $request): Factory|View|Application
    {
        return view('pages.payments.cancelled');
    }
}
