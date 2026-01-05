<?php

namespace App\Http\Gateways;

use App\Exceptions\InvalidDataException;
use App\Http\Gateways\Interfaces\SupportsRedirect;
use App\Http\Gateways\Storage\LineItem;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Location\Currency;
use App\Models\Order\Order;
use App\Models\Order\Payment\PaymentIntention;
use Settings;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\StripeClient;

class StripeGateway extends Gateway implements SupportsRedirect
{
    public const AVAILABLE_SURCHARGES = [
        'USD' => 3.0,
        'CAD' => 2.4,
        'AUD' => 4.0,
        'NZD' => 4.0,
    ];

    private string $success;
    private string $cancelled;

    public function __construct(?string $success = null, ?string $cancelled = null)
    {
        $this->success = $success ?? route('payment.gateway.stripe.success');
        $this->cancelled = $cancelled ?? route('payment.gateway.stripe.cancelled');
    }

    public static function getStripeSurcharge(Currency|string $currency): string|null
    {
        $code = strtoupper($currency instanceof Currency ? $currency->code : $currency);
        $surcharge = (float)setting('currency.surcharge.stripe.' . $code, 0.0);
        if (empty($surcharge)) { $surcharge = null; }
        return $surcharge;
    }

    /**
     * @throws InvalidDataException
     */
    public static function setStripeSurcharge(Currency|string $currency, float|null $surcharge): void
    {
        $code = strtoupper($currency instanceof Currency ? $currency->code : $currency);
        $surcharge = empty($surcharge) ? null : (float)$surcharge;
        if (!array_key_exists($code, self::AVAILABLE_SURCHARGES)) {
            throw new InvalidDataException("Currency {$code} is not available for surcharges.");
        }
        $max = self::AVAILABLE_SURCHARGES[$code];
        if ($surcharge !== null && $surcharge > $max) {
            throw new InvalidDataException("Maximum surcharge for {$code} is {$max}%");
        }
        Settings::set('currency.surcharge.stripe.' . $code, $surcharge);
    }

    public static function getAmountForSurcharge(Currency|string $currency, float|null $amount): int
    {
        $surcharge = self::getStripeSurcharge($currency);
        if ($surcharge !== null) {
            $sAmount = $amount * ($surcharge / 100);
            $sAmount -= ($sAmount % 100);
            return (int)$sAmount;
        }
        return 0;
    }

    /**
     * @inheritDoc
     * @throws ApiErrorException
     * @noinspection PhpArrayKeyDoesNotMatchArrayShapeInspection
     */
    public function getRedirect(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string
    {
        return $this->getCheckout($items, $intention, $customer, $success)->url;
    }

    /**
     * @param array $items
     * @param PaymentIntention $intention
     * @param Customer|BookingTraveller $customer
     * @param string|null $success
     * @param string|null $currency
     * @return array{intent: string, secret: string}
     */
    public function getCheckoutSecret(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null, string|null $currency = null): array
    {
        $intent = $this->getStripePaymentIntent($items, $intention, $customer, $success, $currency);
        return ['intent' => $intent->id, 'secret' => $intent->client_secret,];
    }

    /**
     * @param LineItem[] $items
     * @param PaymentIntention $intention
     * @param Customer|BookingTraveller $customer
     * @param string|null $success
     * @param string $ui
     * @param string|null $currency
     * @return Session
     * @throws ApiErrorException
     */
    private function getCheckout(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null, string $ui = 'hosted', string|null $currency = null): Session
    {
        $currency = $currency ?? (($intention->getRelatedModel() instanceof Order) ? $intention->getRelatedModel()?->currency?->code : null);
        $currencyKeys = config('app.gateways.stripe.currencies.' . strtoupper($currency), []);
        $secret = $currencyKeys['secret'] ?? config('app.gateways.stripe.secret');
        $currencyKeys = config('app.gateways.stripe.currencies.' . strtoupper($currency), []);
        $secret = $currencyKeys['secret'] ?? config('app.gateways.stripe.secret');
        $currency = strtolower(empty($currency) ? Settings::currency()?->code : $currency);
        $lineItems = [];
        $total = 0;

        foreach ($items as $item) {
            $lineItems[] = $item->toStripe($currency);
            $total += (float)$item->quantity * $item->cost;
        }

        $stripe = new StripeClient($secret);

        $data = [
            'line_items' => $lineItems,
            'mode' => 'payment',
            'payment_intent_data' => [
                'metadata' => [
                    'intention_id' => $intention->id,
                ],
            ],
            'currency' => $currency,
            'customer_email' => $customer?->email_address,
            'metadata' => [
                'intention_id' => $intention->id,
                'booking_reference' => $intention->reference,
            ],
            'ui_mode' => $ui,
        ];

        if ($ui === 'custom') {
            $data = [
                ...$data,
                'return_url' => $success ?? $this->success,
            ];
        } else {
            $data = [
                ...$data,
                'success_url' => $success ?? $this->success,
                'cancel_url' => $this->cancelled,
            ];
        }

        return $stripe->checkout->sessions->create($data);
    }

    private function getStripePaymentIntent(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null, string|null $currency = null): PaymentIntent
    {
        $currency = $currency ?? (($intention->getRelatedModel() instanceof Order) ? $intention->getRelatedModel()?->currency?->code : null);
        $currencyKeys = config('app.gateways.stripe.currencies.' . strtoupper($currency), []);
        $secret = $currencyKeys['secret'] ?? config('app.gateways.stripe.secret');
        $currency = strtolower(empty($currency) ? Settings::currency()?->code : $currency);
        $total = 0;

        foreach ($items as $item) {
            $total += (float)$item->quantity * $item->cost;
        }

        $stripe = new StripeClient($secret);

        $data = [
            'amount' => $total * 100,
            'currency' => $currency,
            'metadata' => [
                'intention_id' => $intention->id,
                'booking_reference' => $intention->reference,
            ],
        ];
        return $stripe->paymentIntents->create($data);
    }

    public function attachPaymentMethodToIntention(string $secret, string $paymentMethod)
    {
        $stripe = new StripeClient(config('app.gateways.stripe.secret'));

        $stripe->paymentIntents->update($secret, ['payment_method' => $paymentMethod,]);
        $intent = $stripe->paymentIntents->retrieve($secret);
        if ($intent->payment_method_options['card']['surcharge']['status'] === 'available') {
            $surcharge = self::getAmountForSurcharge($intent->currency, $intent->amount);
            if (!empty($surcharge)) {
                $stripe->paymentIntents->update($secret, ['amount_surcharge' => $surcharge,]);
            }
        }
    }

    public static function getPaymentIntent($secret)
    {
        return (new StripeClient(config('app.gateways.stripe.secret')))->paymentIntents->retrieve($secret);
    }

    public function checkout(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string
    {
        return $this->getRedirect($items, $intention, $customer, $success);
    }

    public function process(string $reference, float $amount, mixed $created = null, string|null $currency = null, float|null $surcharge = null): void
    {
        $currency = $currency ?? config('app.currency');
        $intention = PaymentIntention::fetch($reference);
        if (!isset($intention)) return;
        $this->processIntention($intention, $amount - ($surcharge ?? 0.0), 'Stripe', $created, $currency, $surcharge);
    }
}
