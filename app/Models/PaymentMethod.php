<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends SimpleModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:payment_methods,name'];
    }

    public static function firstOrCreate(string $name)
    {
        $type = self::where('name', '=', $name)->first();
        if (!isset($type)) {
            $type = self::create(['name' => $name,]);
        }
        return $type;
    }

    public function __toString()
    {
        return $this->name;
    }
}
