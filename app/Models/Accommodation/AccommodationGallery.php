<?php

namespace App\Models\Accommodation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationGallery extends Model
{
    use HasFactory;

    protected $fillable = ['accommodation_id', 'image_url'];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }
}