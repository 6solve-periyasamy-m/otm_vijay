<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccommodationInventoryTourUpgrade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['upgrade_id','description'];

    public function base(): BelongsTo
    {
        return $this->belongsTo(AccommodationInventoryTour::class, 'base_id');
    }

    public function upgrade(): BelongsTo
    {
        return $this->belongsTo(AccommodationInventoryTour::class, 'upgrade_id');
    }
}
