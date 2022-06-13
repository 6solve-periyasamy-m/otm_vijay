<?php

namespace App\Models\Tour;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Activity\ActivityInventory;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Flight\FlightInventory;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Order\Order;
use App\Models\Transport\TransportInventory;
use App\Models\Transport\TransportInventoryTour;
use App\Repository\Model\Tour\TourRepository;
use App\Repository\RoomingRepository;
use App\Repository\StockRepository;
use Database\Factories\Tour\TourFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Tour\Tour
 *
 * @property int $id
 * @property int|null $event_id
 * @property string $name
 * @property string|null $description
 * @property string|null $notes
 * @property float $base_price_per_person
 * @property float $margin
 * @property float $single_occupancy_surcharge
 * @property float $deposit
 * @property bool $stock_control_active
 * @property int|null $stock
 * @property string|null $booking_form_url
 * @property int|null $tour_category_id
 * @property int|null $tour_merchandise_id
 * @property bool $is_active
 * @property Carbon $date_from
 * @property Carbon $date_to
 * @property string|null $invoice_footer
 * @property string $terms Terms and Conditions of purchasing this tour
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
 * @property-read bool $has_atol_certificate
 * @property-read float $remaining_installment
 * @property-read float $remaining_percentage
 * @property-read Collection $templates
 * @property-read Collection|Merchandise[] $merchandise
 * @property-read int|null $merchandise_count
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read Collection|PaymentInstallment[] $paymentInstallments
 * @property-read int|null $payment_installments_count
 * @property-read Collection|TransportInventory[] $transportInventory
 * @property-read int|null $transport_inventory_count
 * @property-read Collection|TransportInventoryTour[] $transportInventoryTours
 * @property-read int|null $transport_inventory_tours_count
 * @property-read TourRepository $repository
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
 * @method static Builder|Tour whereTerms($value)
 * @method static Builder|Tour whereTourCategoryId($value)
 * @method static Builder|Tour whereTourMerchandiseId($value)
 * @method static Builder|Tour whereUpdatedAt($value)
 * @method static QueryBuilder|Tour withTrashed()
 * @method static QueryBuilder|Tour withoutTrashed()
 * @mixin Eloquent
 */
class Tour extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['event_id', 'name', 'description', 'date_from', 'date_to', 'base_price_per_person', 'margin', 'single_occupancy_surcharge', 'stock_control_active', 'stock', 'deposit', 'booking_form_url', 'tour_category_id', 'is_active', 'notes', 'invoice_footer', 'final_payment', 'terms'];
    protected $casts = ['date_from' => 'date', 'date_to' => 'date', 'final_payment' => 'date', 'is_active' => 'boolean',
        'base_price_per_person' => 'double','deposit' => 'double', 'margin' => 'double', 'stock_control_active' => 'boolean'];
    protected array $cascadeDeletes = ['accommodationInventoryTours', 'activityInventoryTours', 'flightInventoryTours', 'transportInventoryTours', 'merchandise', 'paymentInstallments'];

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

    public function flightInventory(): BelongsToMany
    {
        return $this->belongsToMany(FlightInventory::class, 'flight_inventory_tours')->withPivot('sales_price', 'tour_component_type');
    }

    public function accommodationInventory(): BelongsToMany
    {
        return $this->belongsToMany(AccommodationInventory::class, 'accommodation_inventory_tours')->withPivot('sales_price', 'tour_component_type');
    }

    public function activityInventory(): BelongsToMany
    {
        return $this->belongsToMany(ActivityInventory::class, 'activity_inventory_tours')->withPivot('sales_price', 'tour_component_type');
    }

    public function transportInventory(): BelongsToMany
    {
        return $this->belongsToMany(TransportInventory::class, 'transport_inventory_tours')->withPivot('sales_price', 'tour_component_type');
    }

    public function accommodationInventoryTours(): HasMany
    {
        return $this->hasMany(AccommodationInventoryTour::class, 'tour_id');
    }

    public function activityInventoryTours(): HasMany
    {
        return $this->hasMany(ActivityInventoryTour::class, 'tour_id');
    }

    public function flightInventoryTours(): HasMany
    {
        return $this->hasMany(FlightInventoryTour::class, 'tour_id');
    }

    public function transportInventoryTours(): HasMany
    {
        return $this->hasMany(TransportInventoryTour::class, 'tour_id');
    }

    public function paymentInstallments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class, 'tour_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'tour_id');
    }

    public function merchandise(): HasMany
    {
        return $this->hasMany(Merchandise::class, 'tour_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TourCategory::class, 'tour_category_id');
    }

    public function getUsedStock(): int
    {
        return $this->repository->getUsedStock();
    }

    public function getRemainingInstallmentAttribute(): float
    {
        $cost = $this->base_price_per_person - $this->deposit;
        $cost -= $this->paymentInstallments()->sum('amount');
        return $cost;
    }

    public function getDepositPercentageAttribute(): float
    {
        return $this->base_price_per_person == 0 ? 0 : round(($this->deposit / $this->base_price_per_person) * 100, 2);
    }

    public function getRemainingPercentageAttribute(): float
    {
        return $this->base_price_per_person == 0 ? 0 : round(($this->remaining_installment / $this->base_price_per_person) * 100, 2);
    }

    public function getHasAtolCertificateAttribute(): bool
    {
        return $this->flightInventoryTours()->count() > 0;
    }

    public function getAccommodationTemplateData(): array
    {
        return TourRepository::getTemplateData($this);
    }

    public function getTemplatesAttribute(): \Illuminate\Support\Collection
    {
        return RoomingRepository::getTemplateTourInventory($this);
    }

    public function clone(): Tour
    {
        return TourRepository::clone($this);
    }

    public function getRepositoryAttribute(): TourRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new TourRepository($this);
        return $this->internal_repository;
    }
}
