<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentInstallment;

class PaymentSchedule extends Model
{
    use HasFactory;

    public $additional_attributes = ['Tour_Payment_Schedule'];

    public function PaymentInstallment() 
    {
        return $this->hasMany(PaymentInstallment::class);
    }
    public function Tour() 
    {
        return $this->belongsTo(Tour::class);
    }
    public function getTourPaymentScheduleAttribute() 
    {
        return "Tour: {$this->tour->title} Amount: {$this->amount}";
    }
}
