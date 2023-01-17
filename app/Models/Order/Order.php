<?php

namespace App\Models\Order;

use App\Models\Customer\Customer;
use App\Models\Customer\Group;
use App\Models\Customer\OrderCustomerGroup;
use App\Models\Helper\OrderStatus;
use App\Models\Order\Adjustment\ManualAdjustment;
use App\Models\Order\Payment\Payment;
use App\Models\Order\Payment\PaymentReminder;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Repository\Model\Order\OrderRepository;
use Database\Factories\Order\OrderFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * App\Models\Order\Order
 *
 * @property int $id
 * @property int $tour_id
 * @property int|null $lead_booker_id
 * @property string|null $booking_reference Unique reference for the booking
 * @property float|null $deposit The expected deposit amount
 * @property float|null $booking_fee The fee paid at time of booking
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
 * @property-read int|null $days_until_next_payment The number of days until the next payment is due, or null if all installments are paid
 * @property-read Collection|Customer[] $customers The customers associated with this order
 * @property-read int|null $customers_count The amount of customers associated with this order
 * @property-read OrderRepository $repository The repository used for calculations
 * @property-read float $calculated_deposit The calculated deposit based on customer count
 * @property-read float $cost The cost of the order before adjustments
 * @property-read int $customer_count The amount of customers on the order
 * @property-read int $paying_customers The amount of customers on the order that are paying
 * @property-read string $customer_names String list of all customer full names
 * @property-read float $deposit_percentage What percentage of the total cost is the deposit
 * @property-read bool $has_atol Whether this order has an ATOL certificate
 * @property-read string $lead_booker_name Full name of the lead booker
 * @property-read float $paid The total amount paid for the order
 * @property-read float $remaining Remaining amount left to be paid
 * @property-read float $remaining_installment Remaining cost on the due installment
 * @property-read float $remaining_percentage Percentage of the total cost left to be paid after deposit and installments
 * @property-read float $order_adjustment_total The sum of all order adjustments, not including customer adjustments
 * @property-read float $total_adjustments The sum of all adjustments on the order and customers
 * @property-read OrderStatus $status The status of the order
 * @property-read Quote|null $quote The quote the order was built from
 * @property-read Collection|Group[] $groups List of groups
 * @property-read float $total The total cost of the order
 * @property-read OrderInstallment|null $next_installment A temporary installment with details of the next payment, or null if all installments are paid
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
    use SoftDeletes, CascadeSoftDeletes, HasFactory, HasRelationships;

    protected $fillable = ['quote_id', 'tour_id', 'lead_booker_id', 'token', 'booking_reference', 'ordered_on', 'internal_notes', 'external_notes', 'deposit', 'invoice_footer', 'booking_fee'];
    protected $casts = ['ordered_on' => 'datetime', 'cancelled' => 'boolean', 'deposit' => 'double',];

    protected array $cascadeDeletes = ['orderCustomers', 'payments', 'adjustments', 'installments', 'invoices'];

    private OrderRepository $internal_repository;

    public static function getValidationRules(): array
    {
        return [
            'ordered_on' => 'required|date'
        ];
    }

    public static function generateBookingReference(Order $order): string
    {
        return setting('booking.prefix')
            . str_pad($order->tour->id, 4, '0', STR_PAD_LEFT)
            . str_pad($order->id, 4, '0', STR_PAD_LEFT)
            . str_pad($order->leadBooker->id, 4, '0', STR_PAD_LEFT)
            . substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(4 / strlen($x)))), 1, 4);
    }

    // Relationships

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function orderCustomers(): HasMany
    {
        return $this->hasMany(OrderCustomer::class, 'order_id');
    }

    public function leadBooker(): BelongsTo
    {
        return $this->belongsTo(OrderCustomer::class, 'lead_booker_id');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(PaymentReminder::class, 'order_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'order_id');
    }

    public function installments(): HasMany
    {
        return $this->hasMany(OrderInstallment::class, 'order_id')->orderBy('due_on');
    }

    public function customers(): HasManyThrough
    {
        return $this->hasManyThrough(Customer::class, OrderCustomer::class, 'order_id', 'id', 'id', 'customer_id');
    }

    public function quote(): HasOne
    {
        return $this->hasOne(Quote::class, 'order_id');
    }

    public function groups(): HasManyDeep
    {
        return $this->hasManyDeep(Group::class, [OrderCustomer::class, OrderCustomerGroup::class,])->groupBy('groups.id');
    }

    /**
     * @return float The sum of all adjustments on the order and customers
     */
    public function getTotalAdjustmentsAttribute(): float
    {
        return $this->repository->getTotalAdjustedValue();
    }

    /**
     * @return OrderInstallment|null A temporary installment with details of the next payment, or null if all installments are paid
     */
    public function getNextInstallmentAttribute(): ?OrderInstallment
    {
        return $this->repository->getNextPaymentDetails();
    }

    // Attributes

    /**
     * @return string The full name of the lead booker
     */
    public function getLeadBookerNameAttribute(): string
    {
        return $this->leadBooker->customer_name;
    }

    /**
     * @return OrderStatus The current status of the order
     */
    public function getStatusAttribute(): OrderStatus
    {
        return $this->repository->getOrderStatus();
    }

    /**
     * @return float The total amount paid against the order
     */
    public function getPaidAttribute(): float
    {
        return $this->payments()->sum('amount');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    /**
     * @return float The total cost of the order, or the amount paid if the order is cancelled
     */
    public function getTotalAttribute(): float
    {
        return $this->cancelled ? $this->paid : ($this->cost + $this->total_adjustments);
    }

    /**
     * @return float A summation of all components on the order that cost money (Not including adjustments)
     */
    public function getCostAttribute(): float
    {
        return $this->repository->getCost();
    }

    /**
     * @return float The amount left to be paid on the order
     */
    public function getRemainingAttribute(): float
    {
        return $this->cancelled ? 0 : $this->repository->getRemaining();
    }

    /**
     * @return float How much is left to be paid after the deposit and all installments
     */
    public function getRemainingInstallmentAttribute(): float
    {
        $cost = $this->cost - $this->calculated_deposit + $this->total_adjustments;
        foreach ($this->installments as $installment) {
            $cost -= $installment->calculated_amount;
        }
        return $cost;
    }

    /**
     * @return int The amount of customers on the order
     */
    public function getCustomerCountAttribute(): int
    {
        return $this->orderCustomers->count();
    }

    /**
     * @return float What percentage of the total cost is the deposit, or 0 if the cost is 0
     */
    public function getDepositPercentageAttribute(): float
    {
        return $this->total == 0 ? 0 : round(($this->calculated_deposit / $this->total) * 100, 2);
    }

    /**
     * @return float What percentage of the total cost is the remaining installment, or 0 if the cost is 0
     */
    public function getRemainingPercentageAttribute(): float
    {
        return $this->total == 0 ? 0 : round(($this->remaining_installment / $this->total) * 100, 2);
    }

    /**
     * @return float The required deposit, calculated from customer count
     */
    public function getCalculatedDepositAttribute(): float
    {
        return $this->deposit * $this->paying_customers;
    }

    /**
     * @return string Concatenated String of all customer names
     */
    public function getCustomerNamesAttribute(): string
    {
        $names = "";
        foreach ($this->orderCustomers as $orderCustomer) {
            $names .= "{$orderCustomer->customer_name}, ";
        }
        return substr($names, 0, -2);
    }

    /**
     * @return bool Does this order contain any ATOL-protected flights?
     */
    public function getHasAtolAttribute(): bool
    {
        return $this->tour->protected && $this->repository->hasFlight();
    }

    /**
     * @return int|null Returns the days from now, or null if all installments are paid
     */
    public function getDaysUntilNextPaymentAttribute(): ?int
    {
        $next = $this->next_installment?->due_on;
        if (!isset($next)) return null;
        $next = Carbon::parse($next);
        return $next->isBefore(Carbon::now()) ? ($next->diffInDays(Carbon::now())) * -1 : ($next->diffInDays(Carbon::now()));
    }

    /**
     * @return float The sum of all order adjustments, not including customer adjustments
     */
    public function getOrderAdjustmentTotalAttribute(): float
    {
        return $this->adjustments()->sum('amount');
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(ManualAdjustment::class, 'order_id');
    }

    public function getRepositoryAttribute(): OrderRepository
    {
        if (!isset ($this->internal_repository)) $this->internal_repository = new OrderRepository($this);
        return $this->internal_repository;
    }

    public function getPayingCustomersAttribute(): int
    {
        return $this->orderCustomers()->where('is_charged', '=', true)->count();
    }

    // Functions

    /**
     * @return array List of all additional costs for the order
     */
    public function getAdditionalCosts(): array
    {
        return $this->repository->getAdditionalCosts();
    }
}
