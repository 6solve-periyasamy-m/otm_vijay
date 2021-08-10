<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ActivityInventory extends Model
{
    use SoftDeletes;

    protected $casts = [
        'activity_start_date_time' => 'datetime',
        'activity_end_date_time' => 'datetime',
    ];
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function component_type()
    {
        return $this->hasOneThrough(TourComponentType::class, ActivityInventoryTour::class, 'activity_inventory_id', 'id', 'id');
    }

    public function tour()
    {
        return $this->belongsToMany(Tour::class, 'activity_inventory_tour')->withPivot('sales_price', 'tour_component_type');
    }

    public static function findByTour($tour_id)
    {
        return ActivityInventory::with(['tour' => function ($q) use ($tour_id) {
            $q->where('tour_id', $tour_id);
        }])->get();
    }

    public function getActivityForTourAttribute()
    {
        $activity_start_date_time =  $this->activity_start_date_time->format('d/m/Y H:i');
        $activity_end_date_time = $this->activity_end_date_time->format('d/m/Y H:i');

        return "{$this->activity->title}｜Activity Start: {$activity_start_date_time}｜Activity End: {$activity_end_date_time}｜Ticket Type: {$this->ticketType->ticket_type_name}";
    }

    public $additional_attributes = ['Activity_for_tour'];
}
