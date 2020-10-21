<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jahondust\ModelLog\Traits\ModelLogging;


class AccommodationInventory extends Model
{
    use HasFactory, ModelLogging;
}
    $logFields = ['accommodation_id','purchase_price'];
