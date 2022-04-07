<?php

namespace App\Models\Tour;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'starts_at', 'ends_at', 'booking_url', 'notes',];
    protected $casts = ['starts_at' => 'date', 'ends_at' => 'date'];

    public static function getValidationRules(): array
    {
        return [
            'name' => 'required',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
        ];
    }

    function getEventDetailsAttribute(): string
    {
        return $this->name . ' - ' . Carbon::parse($this->starts_at)->format('d/m/Y') . ' : ' . Carbon::parse($this->ends_at)->format('d/m/Y');
    }

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class, 'event_id');
    }
}
