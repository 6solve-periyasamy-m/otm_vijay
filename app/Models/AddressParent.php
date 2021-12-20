<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddressParent extends Model
{
    use HasFactory, SoftDeletes;

    const ID_MAP = [
        63 => 'Other',
        1 => 'Customer',
        2 => 'Accommodation',
        3 => 'Activity',
        4 => 'Airport',
        5 => 'Transport',
    ];

    public static function getParentId(string $key)
    {
        switch (strtolower($key)) {
            case 'customer':
                return 1;
            case 'accommodation':
                return 2;
            case 'activity':
                return 3;
            case 'airport':
                return 4;
            case 'transport':
                return 5;
            default:
                return 63;
        }
    }
}
