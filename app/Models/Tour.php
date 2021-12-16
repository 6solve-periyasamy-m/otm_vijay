<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['event_id', 'name', 'description', 'date_from', 'date_to', 'base_price_per_person', 'margin', 'single_occupancy_surcharge', 'stock_control_active', 'stock', 'deposit', 'booking_form_url', 'tour_category_id', 'is_active', 'notes',];

    public static function getValidationRules()
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
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function paymentSchedule()
    {
        return $this->hasMany(PaymentSchedule::class, 'payment_schedule');
    }

    public function flightInventory()
    {
        return $this->belongsToMany(FlightInventory::class, 'flight_inventory_tours')->withPivot('sales_price', 'tour_component_type');
    }

    public function accommodationInventory()
    {
        return $this->belongsToMany(AccommodationInventory::class, 'accommodation_inventory_tours')->withPivot('sales_price', 'tour_component_type');
    }

    public function activityInventory()
    {
        return $this->belongsToMany(ActivityInventory::class, 'activity_inventory_tours')->withPivot('sales_price', 'tour_component_type');
    }

    public function transportInventory()
    {
        return $this->belongsToMany(TransportInventory::class, 'transport_inventory_tours')->withPivot('sales_price', 'tour_component_type');
    }

    public function paymentPlan()
    {
        return $this->belongsTo(PaymentPlan::class, 'payment_plan_id');
    }

    // public function flightInventory()
    // {
    //     return $this->belongsTo(FlightInventory::class);
    // }

    // public function activityInventoryTour()
    // {
    //     return $this->belongsToMany(ActivityInventoryTour::class)->withPivot('created_at', 'deleted_at');
    // }

    public function accommodationInventoryTours()
    {
        return $this->hasMany(AccommodationInventoryTour::class, 'tour_id');
    }

    public function activityInventoryTours()
    {
        return $this->hasMany(ActivityInventoryTour::class, 'tour_id');
    }

    public function flightInventoryTours()
    {
        return $this->hasMany(FlightInventoryTour::class, 'tour_id');
    }

    public function transportInventoryTours()
    {
        return $this->hasMany(TransportInventoryTour::class, 'tour_id');
    }

    public function paymentInstallments()
    {
        return $this->hasMany(PaymentInstallment::class, 'tour_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'tour_id');
    }

    public function merchandise()
    {
        return $this->hasMany(Merchandise::class, 'tour_id');
    }

    public function category()
    {
        return $this->belongsTo(TourCategory::class, 'tour_category_id');
    }
}
