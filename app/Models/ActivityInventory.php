<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ActivityInventory extends Model
{
    use SoftDeletes, CascadeSoftDeletes;
    use HasFactory;

    public $additional_attributes = ['Activity_for_tour'];
    protected $fillable = ['activity_id', 'ticket_type_id', 'starts_at', 'ends_at', 'fit_selectable', 'stock', 'purchase_price', 'sales_price', 'currency_id', 'notes',];
    protected $cascadeDeletes = ['tourComponents'];
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public static function getValidationRules()
    {
        return [
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'starts_at' => 'date',
            'ends_at' => 'date',
            'stock' => 'required|numeric|integer',
            'purchase_price' => 'required|numeric',
            'sales_price' => 'required|numeric',
        ];
    }

    public static function findByTour($tour_id)
    {
        return ActivityInventory::with(['tour' => function ($q) use ($tour_id) {
            $q->where('tour_id', $tour_id);
        }])->get();
    }

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

    public function tourComponents()
    {
        return $this->hasMany(ActivityInventoryTour::class, 'activity_inventory_id');
    }

    public function getActivityForTourAttribute()
    {
        $starts_at = $this->starts_at->format('d/m/Y H:i');
        $ends_at = $this->ends_at->format('d/m/Y H:i');

        return "{$this->activity->name}｜Activity Start: {$starts_at}｜Activity End: {$ends_at}｜Ticket Type: {$this->ticketType->name}";
    }
}
