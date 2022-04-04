<?php

namespace App\Models\Order;

use App\Models\Customer;
use App\Models\Helper\OrderStatus;
use App\Models\Invoice;
use App\Models\Order\Adjustment\ManualAdjustment;
use App\Models\Order\Payment\Payment;
use App\Models\PaymentReminder;
use App\Models\Tour;
use App\Repository\OrderRepository;
use App\Repository\SettingsRepository;
use Database\Factories\OrderFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $tour_id
 * @property int|null $lead_booker_id
 * @property string|null $booking_reference Unique reference for the booking
 * @property string|null $deposit The expected deposit amount
 * @property Carbon $ordered_on When the order was placed
 * @property bool $cancelled Is the order cancelled?
 * @property string|null $internal_notes The notes shown only to the operator
 * @property string|null $external_notes The notes visible to the customer
 * @property string|null $invoice_footer The footer to be printed on the invoice
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $token The token used during the booking process
 * @property-read Collection|ManualAdjustment[] $adjustments The manual adjustments on the order
 * @property-read int|null $adjustments_count The amount of manual adjustments on the order
 * @property-read Collection|Customer[] $customers The customers associated with this order
 * @property-read int|null $customers_count The amount of customers associated with this order
 * @property-read float $calculated_deposit The calculated deposit based on customer count
 * @property-read float $cost The cost of the order before adjustments
 * @property-read int $customer_count The amount of customers on the order
 * @property-read string $customer_names String list of all customer full names
 * @property-read float $deposit_percentage What percentage of the total cost is the deposit
 * @property-read bool $has_atol Whether this order has an ATOL certificate
 * @property-read string $lead_booker_name Full name of the lead booker
 * @property-read float $paid The total amount paid for the order
 * @property-read float $remaining Remaining amount left to be paid
 * @property-read float $remaining_installment Remaining cost on the due installment
 * @property-read float $remaining_percentage Percentage of the total cost left to be paid after deposit and installments
 * @property-read OrderStatus $status The status of the order
 * @property-read float $total The total cost of the order
 * @property-read Collection|OrderInstallment[] $installments The installments for the order
 * @property-read int|null $installments_count The amount of installments for the order
 * @property-read Collection|Invoice[] $invoices The invoices for the order
 * @property-read int|null $invoices_count The amount of invoices for the order
 * @property-read OrderCustomer|null $leadBooker The lead booker for the order
 * @property-read Collection|OrderCustomer[] $orderCustomers The order customers
 * @property-read int|null $order_customers_count The amount of order customers
 * @property-read Collection|Payment[] $payments The payments for the order
 * @property-read int|null $payments_count The amount of payments for the order
 * @property-read Collection|PaymentReminder[] $reminders The reminders that have been sent for the order
 * @property-read int|null $reminders_count The amount of reminders that have been sent for the order
 * @property-read Tour $tour The tour that the order was made in relation to
 * @method static OrderFactory factory(...$parameters)
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static QueryBuilder|Order onlyTrashed()
 * @method static Builder|Order query()
 * @method static Builder|Order whereBookingReference($value)
 * @method static Builder|Order whereCancelled($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereDeletedAt($value)
 * @method static Builder|Order whereDeposit($value)
 * @method static Builder|Order whereExternalNotes($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereInternalNotes($value)
 * @method static Builder|Order whereInvoiceFooter($value)
 * @method static Builder|Order whereLeadBookerId($value)
 * @method static Builder|Order whereOrderedOn($value)
 * @method static Builder|Order whereToken($value)
 * @method static Builder|Order whereTourId($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @method static QueryBuilder|Order withTrashed()
 * @method static QueryBuilder|Order withoutTrashed()
 * @mixin Eloquent
 */
class Order extends Model
{
    use SoftDeletes, CascadeSoftDeletes, HasFactory;

    protected $fillable = ['quote_id', 'tour_id', 'lead_booker_id', 'token', 'booking_reference', 'ordered_on', 'internal_notes', 'external_notes', 'deposit', 'invoice_footer'];
    protected $casts = ['ordered_on' => 'datetime', 'cancelled' => 'boolean',];

    protected array $cascadeDeletes = ['orderCustomers', 'payments', 'adjustments'];

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

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
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

    public function customers(): HasManyThrough
    {
        return $this->hasManyThrough(Customer::class, OrderCustomer::class, 'order_id', 'id', 'id', 'customer_id');
    }

    public function getNextInstallment(): array
    {
        return OrderRepository::getNextPaymentDetails($this);
    }

    public function getAdditionals(): array
    {
        return OrderRepository::getOrderAdditionals($this);
    }

    public function getLeadBookerNameAttribute(): string
    {
        return $this->leadBooker->customer_name;
    }

    public function getStatusAttribute(): OrderStatus
    {
        return OrderRepository::getOrderStatus($this);
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
