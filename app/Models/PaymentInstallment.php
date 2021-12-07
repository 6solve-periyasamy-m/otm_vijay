<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentInstallment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['due_on', 'amount',];
    protected $casts = ['due_on' => 'date',];

    public static function getValidationRules()
    {
        return ['due_on' => 'required|date', 'amount' => 'required|numeric',];
    }

    public function paymentPlan()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }
}
