<?php

namespace App\Http\Gateways;

use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Order\Payment\PaymentIntention;
use App\Models\System\GatewayPaymentLink;
use Carbon\Carbon;
use Http;

class FellohGateway extends Gateway
{
    private string $token;
    private int $expiry;
    private static string $GATEWAY = 'Felloh';

    public function __construct()
    {
        $this->url = 'https://' . config('app.gateways.felloh.env', 'api') . '.fellow.com';
        $this->renewToken();
    }

    public function checkout(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string
    {
        $this->renewToken();
        $cost = 0;
        $description = "";
        foreach ($items as $item) {
            $cost += $item->cost * 100;
            $description .= $item->name . ", ";
        }
        $description = substr($description, 0, -2);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->post($this->url . '/agent/payment-links', [
            'customer_name' => "{$customer->first_name} {$customer->last_name}",
            'email' => $customer->email_address,
            'organisation' => config('app.gateways.felloh.organisation'),
            'amount' => $cost,
            'open_banking_enabled' => true,
            'card_enabled' => true,
            'description' => $description,
        ]);
        $id = $response->json('data.id');
        GatewayPaymentLink::create([
            'gateway' => self::$GATEWAY,
            'payment_reference' => $id,
            'payment_intention_id' => $intention->id,
        ]);
        return "https://pay.felloh.com/{$id}";
    }

    public function process(string $reference, float $amount, string $created = null): void
    {
        $intention = GatewayPaymentLink::get(self::$GATEWAY, $reference)?->intention;
        if (!isset($intention)) return;
        $order = $this->processIntention($intention, $amount, self::$GATEWAY, $created);
        $this->linkPayment($order, $reference);
    }

    /**
     * @param string $from Should be passed as Y-m-d
     * @return void
     */
    public function fetchSince(string $from): void
    {
        $this->renewToken();
        $fetch = $this->performFetch($from);
        foreach ($fetch['items'] as $id => $data) {
            $this->process($id, $data['amount'], $data['date']);
        }
        for ($x = 100; $x < $fetch['count']; $x = $x+100) {
            $fetch = $this->performFetch($from);
            foreach ($fetch['items'] as $id => $data) {
                $this->process($id, $data['amount'], $data['date']);
            }
        }
    }

    private function performFetch(string $from, int $start = 0): array
    {

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->post("{$this->url}/agent/transactions ", [
            'organisation' => config('app.gateways.felloh.organisation'),
            'date_from' => $from,
            'skip' => $start,
            'take' => 100,
            'statuses' => ['COMPLETED',]
        ]);
        $data = [];
        foreach ($response->json('data') as $item) {
            $data[$item['payment_link']['id']] = ['amount' => $item['amount'], 'date' => Carbon::parse($item['completed_at']),];
        }
        return ['count' => $response->json('meta.count'), 'start' => $start, 'items' => $data,];
    }

    private function renewToken(): void
    {
        if (!isset($this->expiry) || now()->unix() >= $this->expiry) {
            $token = $this->getApiToken();
            $this->token = $token['token'];
            $this->expiry = intval($token['expiry']);
        }
    }

    private function getApiToken(): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post("{$this->url}/token", [
            'public_key' => config('app.gateways.felloh.public'),
            'private_key' => config('app.gateways.felloh.private'),
        ]);
        return ['token' => $response->json('data.token'), 'expiry' => $response->json('data.expiry'),];
    }

    private function createBooking(Order $order): string
    {
        $this->renewToken();
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->put($this->url . '/agent/bookings', [
            'organisation' => config('app.gateways.felloh.organisation'),
            'customer_name' => "{$order->leadBooker->customer->first_name} {$order->leadBooker->customer->last_name}",
            'email' => $order->leadBooker->customer->email_address,
            'booking_reference' => $order->booking_reference,
            'departure_date' => $order->tour->date_from->format('Y-m-d'),
            'return_date' => $order->tour->date_to->format('Y-m-d'),
            'gross_amount' => $order->cost,
        ]);
        return $response->json('data.id');
    }

    private function getBooking(Order $order): ?string
    {
        $this->renewToken();
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->post("{$this->url}/agent/bookings", [
            'booking_reference' => $order->booking_reference,
        ]);
        $data = $response->json('data');
        if (sizeof($data) === 0) return null;
        return $data[0]['id'];
    }

    private function linkPayment(Order $order, string $payment): void
    {
        $this->renewToken();
        $booking = $this->getBooking($order);
        if (!isset($booking)) {
            $booking = $this->createBooking($order);
        }
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->post("{$this->url}/agent/payment-links/{$payment}/assign", [
            'booking_id' => $booking,
        ]);
    }
}
