<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressParent extends Model
{
    use HasFactory;

    const ID_MAP = [
        63 => 'other',
        1 => 'customer',
        2 => 'accommodation',
        3 => 'activity',
        4 => 'airport',
        5 => 'transport',
    ];

    public static function getParentId(string $key) {
        switch ($key) {
            case 'customer': return 1;
            case 'accommodation': return 2;
            case 'activity': return 3;
            case 'airport': return 4;
            case 'transport': return 5;
            default: return 63;
        }
    }
}
