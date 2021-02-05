<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInstallment extends Model
{
    use HasFactory;

    public function PaymentSchedule() 
    {
        return $this->belongsTo(PaymentSchedule::class);
    }


}
