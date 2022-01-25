<?php

namespace App\Models;

use App\Repository\OrderRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderInstallment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['amount', 'due_on',];
    protected $additional_attributes = ['paid', 'cancelled'];

    public static function getValidationRules()
    {
        return ['due_on' => 'required|date', 'amount' => 'required|numeric',];
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function isCancelled(): bool
    {
        return $this->order->cancelled;
    }

    public function getPaidAttribute(): bool
    {
        return OrderRepository::isInstallmentPaid($this);
    }

    public function getPercentageAttribute(): float
    {
        return round((($this->amount * $this->order->getCustomerCount()) / $this->order->getCost()) * 100, 2);
    }
}
