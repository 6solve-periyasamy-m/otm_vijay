<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReminder extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'payment_installment_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function installment()
    {
        return $this->belongsTo(PaymentInstallment::class);
    }
}
