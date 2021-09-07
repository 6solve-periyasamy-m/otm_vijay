<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes, HasFactory;

    public function quote() 
    {
        return $this->hasOne(Quote::class);
    }

    public function tour()
    {
        return $this->hasOne(Tour::class);
    }

    public function orderStatus()
    {
        return $this->hasOne(OrderStatus::class);
    }
}
