<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Operator extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['name', 'notes',];

    public static function getValidationRules()
    {
        return ['name' => 'required',];
    }

    public function transports()
    {
        return $this->hasMany(Transport::class, 'operator_id');
    }
}
