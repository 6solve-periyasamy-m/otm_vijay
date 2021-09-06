<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManualAdjustment extends Model
{
    use HasFactory, SoftDeletes;

    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
