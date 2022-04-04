<?php

namespace App\Models;

use App\Repository\OrderRepository;
use App\Repository\SettingsRepository;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Order extends Model
{
    use SoftDeletes, CascadeSoftDeletes, HasFactory;

    protected $fillable = ['quote_id', 'tour_id', 'lead_booker_id', 'token', 'booking_reference', 'ordered_on', 'internal_notes', 'external_notes', 'deposit', 'invoice_footer'];
    protected $cascadeDeletes = ['orderCustomers', 'payments', 'adjustments'];
    protected $casts = ['ordered_on' => 'datetime', 'cancelled' => 'boolean',];

    public static function getValidationRules(): array
    {
        return [
            'quote_id' => 'nullable|exists:quotes,id',
            'tour_id' => 'required|exists:tours,id',
            'ordered_on' => 'required|date'
        ];
    }

    public static function generateBookingReference(Order $order): string
    {
        return SettingsRepository::get('booking.prefix')
            . str_pad($order->tour->id, 4, '0', STR_PAD_LEFT)
            . str_pad($order->id, 4, '0', STR_PAD_LEFT)
            . str_pad($order->leadBooker->id, 4, '0', STR_PAD_LEFT)
            . substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(4 / strlen($x)))), 1, 4);
    }

    public function quote(): HasOne
    {
        return $this->hasOne(Quote::class);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function orderStatus(): HasOne
    {
        return $this->hasOne(OrderStatus::class);
    }

    public function orderCustomers(): HasMany
    {
        return $this->hasMany(OrderCustomer::class, 'order_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    public function leadBooker(): BelongsTo
    {
        return $this->belongsTo(OrderCustomer::class, 'lead_booker_id');
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(ManualAdjustment::class, 'order_id');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(PaymentReminder::class, 'order_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'order_id');
    }

    public function getAdjustmentValue(): float
    {
        return OrderRepository::getTotalAdjustedValue($this);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(OrderInstallment::class, 'order_id')->orderBy('due_on');
    }

    public function getNextInstallment(): array
    {
        return OrderRepository::getNextPaymentDetails($this);
    }

    public function getAdditionals(): array
    {
        return OrderRepository::getOrderAdditionals($this);
    }

    public function customers(): Collection
    {
        return OrderRepository::getCustomersForOrder($this);
    }

    public function getLeadBookerNameAttribute(): string
    {
        return $this->leadBooker->customer_name;
    }

    public function getStatusAttribute(): string
    {
        return $this->getStatus()['status'];
    }

    public function getStatus(): array
    {
        return self::getStatusArray(OrderRepository::getOrderStatus($this));
    }

    public static function getStatusArray(int $status): array
    {
        return match ($status) {
            -3 => ['status' => trans('custom.order.status.cancelled.full'), 'color' => 'secondary',],
            -2 => ['status' => trans('custom.order.status.cancelled.deposit'), 'color' => 'secondary',],
            -1 => ['status' => trans('custom.order.status.cancelled.required'), 'color' => 'secondary',],
            0 => ['status' => trans('custom.order.status.full'), 'color' => 'success'],
            1 => ['status' => trans('custom.order.status.outstanding'), 'color' => 'warning'],
            2 => ['status' => trans('custom.order.status.overdue'), 'color' => 'danger'],
            3 => ['status' => trans('custom.order.status.overpaid'), 'color' => 'info'],
            4 => ['status' => trans('custom.order.status.occupancy'), 'color' => 'dark'],
            default => ['status' => 'Status Unknown', 'color' => 'dark'],
        };
    }

    public function getPaidAttribute(): float
    {
        return OrderRepository::getTotalPaid($this);
    }

    public function getTotalAttribute(): float
    {
        return $this->cancelled ? $this->paid : $this->cost;
    }

    public function getCostAttribute(): float
    {
        return OrderRepository::getCost($this);
    }

    public function getRemainingAttribute(): float
    {
        return $this->cancelled ? 0 : OrderRepository::getRemainingToPay($this);
    }

    public function getRemainingInstallmentAttribute(): float
    {
        $cost = $this->cost - $this->calculated_deposit;
        foreach ($this->installments as $installment) {
            $cost -= ($installment->amount) * $this->customer_count;
        }
        return $cost;
    }

    public function getCustomerCountAttribute(): int
    {
        return $this->orderCustomers->count();
    }

    public function getDepositPercentageAttribute(): float
    {
        return $this->cost == 0 ? 0 : round(($this->calculated_deposit / $this->cost) * 100, 2);
    }

    public function getRemainingPercentageAttribute(): float
    {
        return $this->cost == 0 ? 0 : round(($this->remaining_installment / $this->cost) * 100, 2);
    }

    public function getCalculatedDepositAttribute(): float
    {
        return $this->deposit * $this->customer_count;
    }

    public function getCustomerNamesAttribute(): string
    {
        $names = "";
        foreach ($this->orderCustomers as $orderCustomer) {
            $names .= $orderCustomer->customer_name . ', ';
        }
        return substr($names, 0, -2);
    }

    public function groups(): array
    {
        return OrderRepository::getOrderGroups($this);
    }

    public function getHasAtolAttribute(): bool
    {
        return OrderRepository::hasFlight($this);
    }

    public function getAvailableAdditionals(): array
    {
        return OrderRepository::getAllAdditionals($this);
    }
}
