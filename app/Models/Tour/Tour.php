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
use App\Repository\AccommodationComponentRepository;
use App\Repository\StockRepository;
use App\Repository\TourRepository;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Tour extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['event_id', 'name', 'description', 'date_from', 'date_to', 'base_price_per_person', 'margin', 'single_occupancy_surcharge', 'stock_control_active', 'stock', 'deposit', 'booking_form_url', 'tour_category_id', 'is_active', 'notes', 'invoice_footer', 'final_payment','terms'];
    protected $casts = ['date_from' => 'date', 'date_to' => 'date', 'final_payment' => 'date'];
    protected array $cascadeDeletes = ['accommodationInventoryTours','activityInventoryTours','flightInventoryTours','transportInventoryTours','merchandise','paymentInstallments'];

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
        return StockRepository::getTourStock($this);
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

    public function getTemplatesAttribute(): Collection
    {
        return AccommodationComponentRepository::getTemplateTourInventory($this);
    }

    public function clone(): Tour
    {
        return TourRepository::clone($this);
    }
}
