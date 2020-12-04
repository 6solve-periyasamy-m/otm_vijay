<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;

    public function transportInventory()
    {
        return $this->hasMany(TransportInventory::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
