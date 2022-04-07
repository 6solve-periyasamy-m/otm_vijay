<?php

namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityInventoryTourUpgrade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['upgrade_id','description'];

    public function base(): BelongsTo
    {
        return $this->belongsTo(ActivityInventoryTour::class, 'base_id');
    }

    public function upgrade(): BelongsTo
    {
        return $this->belongsTo(ActivityInventoryTour::class, 'upgrade_id');
    }
}
