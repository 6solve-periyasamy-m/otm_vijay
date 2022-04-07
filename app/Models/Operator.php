<?php

namespace App\Models;

use App\Models\Helper\SimpleModel;
use App\Models\Transport\Transport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Operator extends SimpleModel
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name', 'notes',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required',];
    }

    public function transports(): HasMany
    {
        return $this->hasMany(Transport::class, 'operator_id');
    }
}
