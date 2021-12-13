<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    use HasFactory;

    public function tours()
    {
        return $this->hasMany(Tour::class, 'payment_plan_id');
    }

    public function installments()
    {
        return $this->hasMany(PaymentInstallment::class, 'payment_plan_id');
    }
}
