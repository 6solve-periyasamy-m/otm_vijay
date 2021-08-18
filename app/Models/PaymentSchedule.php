<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentInstallment;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentSchedule extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $additional_attributes = ['Tour_Payment_Schedule'];

    public function installments() 
    {
        return $this->hasMany(PaymentInstallment::class);
    }

    public function tour() 
    {
        return $this->belongsTo(Tour::class);
    }

    public function getTourPaymentScheduleAttribute() 
    {
        if (empty($this->tour)) {
            throw new \Exception('Payment schedule for non-existent tour!');
        }

        return "Tour: {$this->tour->title} Amount: {$this->amount}";
    }
}
