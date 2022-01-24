<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentInstallment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['due_on', 'amount','is_percentage'];
    protected $casts = ['due_on' => 'date','is_percentage' => 'boolean'];

    public static function getValidationRules()
    {
        return ['due_on' => 'required|date', 'amount' => 'required|numeric',];
    }

    public function paymentPlan()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function tour()
    {
        return $this->paymentPlan();
    }

    public function getCostAttribute()
    {
        if ($this->is_percentage) {
            return round($this->tour->base_price_per_person * ($this->amount/100), 2);
        } else {
            return $this->amount;
        }
    }

    public function getPercentageAttribute()
    {
        if ($this->is_percentage) {
            return $this->amount;
        } else {
            return round(($this->amount / $this->tour->base_price_per_person) * 100, 2);
        }
    }
}
