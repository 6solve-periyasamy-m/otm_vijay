<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentInstallment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function paymentSchedule() 
    {
        return $this->belongsTo(PaymentSchedule::class);
    }


}
