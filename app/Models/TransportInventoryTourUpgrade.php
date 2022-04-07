<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportInventoryTourUpgrade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['upgrade_id','description'];

    public function base(): BelongsTo
    {
        return $this->belongsTo(TransportInventoryTour::class, 'base_id');
    }

    public function upgrade(): BelongsTo
    {
        return $this->belongsTo(TransportInventoryTour::class, 'upgrade_id');
    }
}
