<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportInventoryTour extends Model
{
    protected $table = 'transport_inventory_tour';
    use HasFactory;
    use SoftDeletes;

    public function transportInventory() {
        return $this->belongsTo(TransportInventory::class, 'transport_inventory_id');
    }
}
