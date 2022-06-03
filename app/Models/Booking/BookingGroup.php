<?php

namespace App\Models\Booking;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Booking\BookingGroup
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BookingGroup newModelQuery()
 * @method static Builder|BookingGroup newQuery()
 * @method static Builder|BookingGroup query()
 * @method static Builder|BookingGroup whereCreatedAt($value)
 * @method static Builder|BookingGroup whereId($value)
 * @method static Builder|BookingGroup whereName($value)
 * @method static Builder|BookingGroup whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BookingGroup extends Model
{
    use HasFactory;
}
