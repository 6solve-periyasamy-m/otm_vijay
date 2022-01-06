<?php

namespace App\Models;

use App\Repository\OrderRepository;
use App\Repository\SettingsRepository;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes, CascadeSoftDeletes, HasFactory;

    protected $fillable = ['quote_id', 'tour_id', 'lead_booker_id', 'token', 'booking_reference', 'ordered_on', 'internal_notes', 'external_notes',];
    protected $cascadeDeletes = ['orderCustomers', 'payments', 'adjustments'];
    protected $casts = ['ordered_on' => 'datetime',];

    public static function getValidationRules()
    {
        return [
            'quote_id' => 'nullable|exists:quotes,id',
            'tour_id' => 'required|exists:tours,id',
            'ordered_on' => 'required|date'
        ];
    }

    public static function generateBookingReference(Order $order)
    {
        return SettingsRepository::get('booking.prefix')
            . str_pad($order->tour->id, 4, '0', STR_PAD_LEFT)
            . str_pad($order->id, 4, '0', STR_PAD_LEFT)
            . str_pad($order->leadBooker->id, 4, '0', STR_PAD_LEFT)
            . substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(4 / strlen($x)))), 1, 4);
    }

    public function quote()
    {
        return $this->hasOne(Quote::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function orderStatus()
    {
        return $this->hasOne(OrderStatus::class);
    }

    public function orderCustomers()
    {
        return $this->hasMany(OrderCustomer::class, 'order_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    public function leadBooker()
    {
        return $this->belongsTo(OrderCustomer::class, 'lead_booker_id');
    }

    public function adjustments()
    {
        return $this->hasMany(ManualAdjustment::class, 'order_id');
    }

    public function reminders()
    {
        return $this->hasMany(PaymentReminder::class, 'order_id');
    }

    public function getStatus()
    {
        return OrderRepository::getOrderStatus($this);
    }

    public function customers() {
        return OrderRepository::getCustomersForOrder($this);
    }
}
