<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManualAdjustment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['order_id', 'amount', 'reason', 'date',];
    protected $casts = ['date' => 'date'];

    public static function getValidationRules()
    {
        return ['date' => 'required|date', 'amount' => 'required|numeric',];
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
