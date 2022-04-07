<?php

namespace App\Models;

use App\Models\Helper\SimpleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddressParent extends SimpleModel
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

    public static function getParentId(string $key): int
    {
        return match (strtolower($key)) {
            'customer' => 1,
            'accommodation' => 2,
            'activity' => 3,
            'airport' => 4,
            'transport' => 5,
            default => 63,
        };
    }
}
