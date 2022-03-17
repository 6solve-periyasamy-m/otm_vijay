<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stripe\PaymentIntent;

class PaymentIntention extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = ['id', 'customer_id', 'reference', 'data', 'type'];

    public static function build(Customer $customer, string $reference, string $type, ?array $data = null): PaymentIntention
    {
        do {
            $key = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(32/strlen($x)) )),1,32);
        } while(self::fetch($key) != null);
        return PaymentIntention::create([
            'id' => $key,
            'reference' => $reference,
            'customer_id' => $customer->id,
            'data' => $data,
            'type' => $type,
        ]);
    }

    public function makePayment(float $amount, PaymentMethod $method, $created): Payment
    {
        return Payment::make([
            'payment_method_id' => $method->id,
            'paid_on' => Carbon::parse($created),
            'customer_id' => $this->customer_id,
            'amount' => $amount,
            'payment_type' => $this->type,
        ]);
    }

    public static function fetch(string $id): ?PaymentIntention
    {
        return PaymentIntention::where('id', '=', $id)->first();
    }
}
