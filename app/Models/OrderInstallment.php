<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderInstallment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['amount', 'due_on',];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
