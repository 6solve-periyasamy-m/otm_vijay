<?php

namespace App\Models\Merchandise;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Merchandise\MerchandiseType
 *
 * @property int $id
 * @property string $name
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MerchandiseType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchandiseType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchandiseType query()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchandiseType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchandiseType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchandiseType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchandiseType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchandiseType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MerchandiseType extends Model
{
    use HasFactory;
}
