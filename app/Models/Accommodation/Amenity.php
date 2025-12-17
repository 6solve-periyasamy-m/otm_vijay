<?php

namespace App\Models\Accommodation;

use App\Models\Helper\SimpleModel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

/**
 * App\Models\Accommodation\Amenity
 *
 * @property int $id
 * @property string $name
 * @property string|null $image_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Amenity newModelQuery()
 * @method static Builder|Amenity newQuery()
 * @method static Builder|Amenity query()
 * @method static Builder|Amenity whereCreatedAt($value)
 * @method static Builder|Amenity whereId($value)
 * @method static Builder|Amenity whereName($value)
 * @method static Builder|Amenity whereImageUrl($value)
 * @method static Builder|Amenity whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Amenity extends SimpleModel
{
    use HasFactory;

    protected $fillable = ['name', 'image_url'];

    public function accommodations()
    {
        return $this->belongsToMany(Accommodation::class, 'accommodation_amenities');
    }

    public function getApiArray(): array
    {
        return [
            'name' => $this->name,
            'image' => asset($this->image_url),
        ];
    }
}