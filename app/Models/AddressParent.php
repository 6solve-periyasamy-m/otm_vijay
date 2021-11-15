<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressParent extends Model
{
    use HasFactory;

    const ID_MAP = [
        0 => 'other',
        1 => 'customer',
        2 => 'accommodation',
        3 => 'activity',
        4 => 'airport',
        5 => 'transport',
    ];
}
