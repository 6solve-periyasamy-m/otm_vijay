<?php

namespace App\Models\Booking;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AccommodationGroup
 *
 * @property int $id
 * @property string $name
 * @method static Builder|AccommodationGroup newModelQuery()
 * @method static Builder|AccommodationGroup newQuery()
 * @method static Builder|AccommodationGroup query()
 * @method static Builder|AccommodationGroup whereId($value)
 * @method static Builder|AccommodationGroup whereName($value)
 * @mixin Eloquent
 */
class AccommodationGroup extends Model
{
    use HasFactory;
    public $timestamps = false;
}
