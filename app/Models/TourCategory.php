<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourCategory extends SimpleModel
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name',];

    public static function getValidationRules()
    {
        return ['name' => 'required'];
    }

    public function tours()
    {
        return $this->hasMany(Tour::class, 'tour_category_id');
    }
}
