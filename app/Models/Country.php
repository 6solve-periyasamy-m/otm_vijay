<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Country extends Model
{
    use SoftDeletes;

    protected $fillable = ['numeric_code','alpha_code','name','dialing_code'];

    public function currencies() {
        return $this->belongsToMany(Currency::class, 'country_currencies');
    }
}
