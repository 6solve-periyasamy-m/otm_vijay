<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Invoice extends Model
{
    use SoftDeletes;
    protected $casts = ['customers' => 'array', 'adjustments' => 'array', 'payments' => 'array',];
    protected $fillable = ['order_id','number','generated','customers','adjustments','payments','footer','total_cost'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
