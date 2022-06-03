<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Booking\BookingGroup
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BookingGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookingGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookingGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|BookingGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BookingGroup extends Model
{
    use HasFactory;
}
