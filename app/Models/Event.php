<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $additional_attributes = ['event_details'];

    protected $fillable = ['name', 'description', 'starts_at', 'ends_at', 'booking_url', 'notes',];
    protected $casts = ['starts_at' => 'date', 'ends_at' => 'date'];

    public static function getValidationRules()
    {
        return [
            'name' => 'required',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
        ];
    }

    function getEventDetailsAttribute()
    {
        return $this->name . ' - ' . Carbon::parse($this->starts_at)->format('d/m/Y') . ' : ' . Carbon::parse($this->ends_at)->format('d/m/Y');
    }

    public function tours()
    {
        return $this->hasMany(Tour::class, 'event_id');
    }
}
