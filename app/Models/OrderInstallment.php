<?php

namespace App\Models;

use App\Models\Order\Order;
use App\Repository\OrderRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderInstallment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['amount', 'due_on',];
    protected $casts = ['due_on' => 'date'];

    public static function getValidationRules(): array
    {
        return ['due_on' => 'required|date', 'amount' => 'required|numeric',];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getCancelledAttribute(): bool
    {
        return $this->order->cancelled;
    }

    public function getPaidAttribute(): bool
    {
        return OrderRepository::isInstallmentPaid($this);
    }

    public function getPercentageAttribute(): float
    {
        return $this->order->cost == 0 ? 100 : round((($this->amount * $this->order->customer_count) / $this->order->cost) * 100, 2);
    }

    public function getCalculatedAmountAttribute(): float
    {
        return $this->amount * $this->order->customer_count;
    }
}
