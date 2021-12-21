<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use HasFactory, SoftDeletes;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}
