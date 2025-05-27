<?php

namespace App\Models\Tour;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Activity\ActivityInventory;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\AdditionalCost;
use App\Models\Booking\Booking;
use App\Models\Flight\FlightInventory;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Helper\Model;
use App\Models\Helper\Traits\HasAdditionalCosts;
use App\Models\Location\Country;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\Order;
use App\Models\Order\OrderInstallment;
use App\Models\System\Brand;
use App\Models\System\TaxBracket;
use App\Models\Transport\TransportInventory;
use App\Models\Transport\TransportInventoryTour;
use App\Models\Voucher\VoucherCode;
use App\Models\Voucher\VoucherTour;
use App\Repository\Model\Tour\TourRepository;
use App\Repository\RoomingRepository;
use Database\Factories\Tour\TourFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Tour\Tour
 *
 * @property int $id
 * @property int|null $event_id
 * @property int|null $tax_bracket_id
 * @property string $name
 * @property string|null $description
 * @property string|null $notes
 * @property float|null $base_price_per_person
 * @property float|null $margin
 * @property float|null $single_occupancy_surcharge
 * @property float|null $deposit
 * @property bool $is_deposit_percentage
 * @property float|null $booking_fee
 * @property bool $stock_control_active
 * @property bool $accommodation_stock_control
 * @property bool $activity_stock_control
 * @property bool $flight_stock_control
 * @property bool $transport_stock_control
 * @property bool $merchandise_stock_control
 * @property int|null $stock
 * @property string|null $booking_form_url
 * @property int|null $tour_category_id
 * @property int|null $tour_merchandise_id
 * @property int|null $brand_id
 * @property bool $is_active
 * @property bool|null $atol_protected NULL if should inherit from system settings (default)
 * @property Carbon $date_from
 * @property Carbon $date_to
 * @property string|null $invoice_footer
 * @property string $terms Terms and Conditions of purchasing this tour
 * @property string|null $payment_details Details for sending payment information *do not use*
 * @property Carbon $final_payment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|AccommodationInventory[] $accommodationInventory
 * @property-read int|null $accommodation_inventory_count
 * @property-read Collection|AccommodationInventoryTour[] $accommodationInventoryTours
 * @property-read int|null $accommodation_inventory_tours_count
 * @property-read Collection|ActivityInventory[] $activityInventory
 * @property-read int|null $activity_inventory_count
 * @property-read Collection|ActivityInventoryTour[] $activityInventoryTours
 * @property-read int|null $activity_inventory_tours_count
 * @property-read TourCategory|null $category
 * @property-read Event|null $event
 * @property-read Collection|FlightInventory[] $flightInventory
 * @property-read int|null $flight_inventory_count
 * @property-read Collection|FlightInventoryTour[] $flightInventoryTours
 * @property-read int|null $flight_inventory_tours_count
 * @property-read float $deposit_percentage
 * @property-read float $deposit_amount
 * @property-read bool $has_atol_certificate
 * @property-read bool $protected
 * @property-read float $remaining_installment
 * @property-read float $remaining_percentage
 * @property-read TourRepository $repository
 * @property-read Brand|null $linkedBrand
 * @property-read Brand $brand
 * @property-read Collection|AccommodationInventoryTour[] $templates
 * @property-read Collection|MerchandiseInventoryTour[] $merchandise
 * @property-read Collection|OrderInstallment[] $orderInstallments All order-installments from non-cancelled orders
 * @property-read int|null $merchandise_count
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read Collection|OrderAccommodation[] $orderAccommodation
 * @property-read Collection|OrderActivity[] $OrderActivities
 * @property-read Collection|OrderFlight[] $OrderFlights
 * @property-read Collection|OrderTransport[] $OrderTransport
 * @property-read int|null $order_accommodation_count
 * @property-read int|null $order_installments_count
 * @property-read Collection|PaymentInstallment[] $paymentInstallments
 * @property-read int|null $payment_installments_count
 * @property-read Collection|TransportInventory[] $transportInventory
 * @property-read int|null $transport_inventory_count
 * @property-read Collection|TransportInventoryTour[] $transportInventoryTours
 * @property-read int|null $transport_inventory_tours_count
 * @property-read Collection<int, AdditionalCost> $costs
 * @property-read int|null $costs_count
 * @property-read Collection<int, VoucherCode> $excludedVouchers
 * @property-read int|null $excluded_vouchers_count
 * @property-read Collection<int, VoucherCode> $includedVouchers
 * @property-read int|null $included_vouchers_count
 * @property-read Collection<int, OrderActivity> $orderActivities
 * @property-read int|null $order_activities_count
 * @property-read Collection<int, OrderFlight> $orderFlights
 * @property-read int|null $order_flights_count
 * @property-read Collection<int, OrderTransport> $orderTransport
 * @property-read int|null $order_transport_count
 * @property-read string $makePaymentDetails Details for documents to include about making a payment
 * @method static TourFactory factory(...$parameters)
 * @method static Builder|Tour newModelQuery()
 * @method static Builder|Tour newQuery()
 * @method static QueryBuilder|Tour onlyTrashed()
 * @method static Builder|Tour query()
 * @method static Builder|Tour whereBasePricePerPerson($value)
 * @method static Builder|Tour whereBookingFormUrl($value)
 * @method static Builder|Tour whereCreatedAt($value)
 * @method static Builder|Tour whereDateFrom($value)
 * @method static Builder|Tour whereDateTo($value)
 * @method static Builder|Tour whereDeletedAt($value)
 * @method static Builder|Tour whereDeposit($value)
 * @method static Builder|Tour whereDescription($value)
 * @method static Builder|Tour whereEventId($value)
 * @method static Builder|Tour whereFinalPayment($value)
 * @method static Builder|Tour whereId($value)
 * @method static Builder|Tour whereInvoiceFooter($value)
 * @method static Builder|Tour whereIsActive($value)
 * @method static Builder|Tour whereMargin($value)
 * @method static Builder|Tour whereName($value)
 * @method static Builder|Tour whereNotes($value)
 * @method static Builder|Tour whereSingleOccupancySurcharge($value)
 * @method static Builder|Tour whereStock($value)
 * @method static Builder|Tour whereStockControlActive($value)
 * @method static Builder|Tour whereAccommodationStockControl($value)
 * @method static Builder|Tour whereActivityStockControl($value)
 * @method static Builder|Tour whereFlightStockControl($value)
 * @method static Builder|Tour whereMerchandiseStockControl($value)
 * @method static Builder|Tour whereTransportStockControl($value)
 * @method static Builder|Tour whereTerms($value)
 * @method static Builder|Tour whereTourCategoryId($value)
 * @method static Builder|Tour whereTourMerchandiseId($value)
 * @method static Builder|Tour whereUpdatedAt($value)
 * @method static QueryBuilder|Tour withTrashed()
 * @method static QueryBuilder|Tour withoutTrashed()
 * @method static Builder|Tour whereAtolProtected($value)
 * @method static Builder|Tour whereBookingFee($value)
 * @method static Builder|Tour whereBrandId($value)
 * @mixin Eloquent
 */
class Tour extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasAdditionalCosts;

    protected $guarded = [];
    protected $casts = [
        'date_from' => 'date:Y-m-d',
        'date_to' => 'date:Y-m-d',
        'final_payment' => 'date:Y-m-d',
        'is_active' => 'boolean',
        'is_deposit_percentage' => 'boolean',
        'base_price_per_person' => 'double',
        'deposit' => 'double',
        'margin' => 'double',
        'stock_control_active' => 'boolean',
        'accommodation_stock_control' => 'boolean',
        'activity_stock_control' => 'boolean',
        'flight_stock_control' => 'boolean',
        'transport_stock_control' => 'boolean',
        'merchandise_stock_control' => 'boolean',
    ];
    protected array $cascadeDeletes = ['accommodationInventoryTours', 'activityInventoryTours', 'flightInventoryTours', 'transportInventoryTours', 'merchandise', 'paymentInstallments', 'voucherPivot'];

    private TourRepository $internal_repository;

    public static function getValidationRules(): array
    {
        return [
            'event_id' => 'nullable|exists:events,id',
            'name' => 'required',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'base_price_per_person' => 'numeric',
            'deposit' => 'numeric',
            'margin' => 'numeric',
            'single_occupancy_surcharge' => 'numeric',
            'stock' => 'required_with:stock_control_active|nullable|numeric|integer',
            'final_payment' => 'required|date',
            'terms' => 'required'
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function bracket(): BelongsTo
    {
        return $this->belongsTo(TaxBracket::class, 'tax_bracket_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'tour_id');
    }

    public function taxBracket(): TaxBracket
    {
        return $this->bracket ?? $this->event?->taxBracket() ?? $this->brand?->taxBracket();
    }

    public function linkedBrand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function accommodationInventory(): HasManyThrough
    {
        return $this->hasManyThrough(
            AccommodationInventory::class,
            AccommodationInventoryTour::class,
            'tour_id',
            'id',
            'id',
            'accommodation_inventory_id'
        );
    }

    public function activityInventory(): HasManyThrough
    {
        return $this->hasManyThrough(
            ActivityInventory::class,
            ActivityInventoryTour::class,
            'tour_id',
            'id',
            'id',
            'activity_inventory_id'
        );
    }

    public function flightInventory(): HasManyThrough
    {
        return $this->hasManyThrough(
            FlightInventory::class,
            FlightInventoryTour::class,
            'tour_id',
            'id',
            'id',
            'flight_inventory_id'
        );
    }

    public function transportInventory(): HasManyThrough
    {
        return $this->hasManyThrough(
            TransportInventory::class,
            TransportInventoryTour::class,
            'tour_id',
            'id',
            'id',
            'transport_inventory_id'
        );
    }

    public function accommodationInventoryTours(): HasMany
    {
        return $this->hasMany(AccommodationInventoryTour::class, 'tour_id');
    }

    public function activityInventoryTours(): HasMany
    {
        return $this->hasMany(ActivityInventoryTour::class, 'tour_id');
    }

    public function orderAccommodation(): HasManyThrough
    {
        return $this->hasManyThrough(OrderAccommodation::class, AccommodationInventoryTour::class, 'tour_id', 'accommodation_inventory_tour_id');
    }

    public function orderActivities(): HasManyThrough
    {
        return $this->hasManyThrough(OrderActivity::class, ActivityInventoryTour::class, 'tour_id', 'activity_inventory_tour_id');
    }

    public function orderFlights(): HasManyThrough
    {
        return $this->hasManyThrough(OrderFlight::class, FlightInventoryTour::class, 'tour_id', 'flight_inventory_tour_id');
    }

    public function orderTransport(): HasManyThrough
    {
        return $this->hasManyThrough(OrderTransport::class, TransportInventoryTour::class, 'tour_id', 'transport_inventory_tour_id');
    }

    public function orderMerchandise(): HasManyThrough
    {
        return $this->hasManyThrough(OrderMerchandise::class, MerchandiseInventoryTour::class, 'tour_id', 'merchandise_inventory_tour_id');
    }

    public function transportInventoryTours(): HasMany
    {
        return $this->hasMany(TransportInventoryTour::class, 'tour_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'tour_id');
    }

    public function merchandise(): HasMany
    {
        return $this->hasMany(MerchandiseInventoryTour::class, 'tour_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TourCategory::class, 'tour_category_id');
    }

    public function orderInstallments(): HasManyThrough
    {
        return $this->hasManyThrough(OrderInstallment::class, Order::class, 'tour_id', 'order_id')->where('cancelled', '=', false);
    }

    public function voucherPivot(): HasMany
    {
        return $this->hasMany(VoucherTour::class, 'tour_id');
    }

    protected function vouchers(): BelongsToMany
    {
        return $this->belongsToMany(VoucherCode::class, 'voucher_tours')->withPivot(['invert']);
    }

    public function includedVouchers(): BelongsToMany
    {
        return $this->vouchers()->where(['invert' => 0,]);
    }

    public function excludedVouchers(): BelongsToMany
    {
        return $this->vouchers()->where(['invert' => 1,]);
    }

    public function getUsedStock(): int
    {
        return $this->repository->getUsedStock();
    }

    public function getRemainingInstallmentAttribute(): float
    {
        $cost = $this->base_price_per_person - $this->deposit_amount;
        foreach ($this->paymentInstallments as $installment) {
            $cost -= $installment->cost;
        }
        return $cost;
    }

    public function paymentInstallments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class, 'tour_id')->orderBy('due_on');
    }

    public function getDepositPercentageAttribute(): float
    {
        return $this->is_deposit_percentage ? ($this->deposit ?? 0.0)
            : ($this->base_price_per_person == 0 ? 0 : round(($this->deposit / $this->base_price_per_person) * 100, 2));
    }

    public function getRemainingPercentageAttribute(): float
    {
        return $this->base_price_per_person == 0 ? 0 : round(($this->remaining_installment / $this->base_price_per_person) * 100, 2);
    }

    public function getHasAtolCertificateAttribute(): bool
    {
        return $this->flightInventoryTours()->count() > 0;
    }

    public function getBrandAttribute(): Brand
    {
        return $this->linkedBrand ?? Brand::getSystemBrand();
    }

    public function setBrandAttribute(Brand $brand)
    {
        $this->brand_id = $brand->id;
        $this->save();
    }

    public function flightInventoryTours(): HasMany
    {
        return $this->hasMany(FlightInventoryTour::class, 'tour_id');
    }

    public function getAccommodationTemplateData(): array
    {
        return $this->repository->getTemplateData();
    }

    public function getBookingFormUrl(Booking|null $booking = null, bool $checkout = false): string|null
    {
        if ($this->booking_form_url === null) {
            return null;
        }
        if (config('app.features.kpt', false) || config('app.features.bleeding-edge')) {
            if ($checkout && $booking !== null) {
                return route('booking.simple.checkout', ['tour' => $this->booking_form_url, 'token' => $booking?->token]);
            }
            return route('booking.simple.index', ['tour' => $this->booking_form_url, 'token' => $booking?->token]);
        }

        if ($checkout && $booking !== null) {
            return route('customer-booking.summary', ['bookingUrl' => $this->booking_form_url, 'token' => $booking?->token]);
        }
        return route('customer-booking.index', ['bookingUrl' => $this->booking_form_url, 'token' => $booking?->token]);
    }

    public function getProtectedAttribute(): bool
    {
        return $this->atol_protected ?? flag('atol.enabled', true);
    }

    public function getTemplatesAttribute(): \Illuminate\Support\Collection
    {
        return RoomingRepository::getTemplateTourInventory($this);
    }

    public function clone(): Tour
    {
        return $this->repository->duplicate();
    }

    public function getRepositoryAttribute(): TourRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new TourRepository($this);
        return $this->internal_repository;
    }

    public function getDepositAmountAttribute(): ?float
    {
        return $this->is_deposit_percentage ? sigfig($this->base_price_per_person * ($this->deposit / 100)) : $this->deposit;
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function getMakePaymentDetailsAttribute(): string
    {
        if (empty($this->payment_details)) {
            return setting('company.bank_transfer', "");
        }
        return $this->payment_details;
    }
}
