<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReminder extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'order_installment_id', 'period'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function installment()
    {
        return $this->belongsTo(OrderInstallment::class);
    }
}
