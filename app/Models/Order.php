<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
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
