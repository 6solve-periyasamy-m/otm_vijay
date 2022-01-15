<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules()
    {
        return ['name' => 'required|unique:payment_methods,name'];
    }

    public static function firstOrCreate(string $name) {
        $type = self::where('name', '=', $name)->first();
        if (!isset($type)) {
            $type = self::create(['name' => $name,]);
        }
        return $type;
    }
}
