<?php

namespace App\Models;

use App\Models\Helper\SimpleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourCategory extends SimpleModel
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required'];
    }

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class, 'tour_category_id');
    }
}
