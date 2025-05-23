<?php

namespace App\Models\Activity;

use App\Models\Helper\SimpleModel;
use Database\Factories\Activity\SessionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * \App\Models\Activity\Session
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static SessionFactory factory($count = null, $state = [])
 * @method static Builder|Session newModelQuery()
 * @method static Builder|Session newQuery()
 * @method static Builder|Session query()
 * @method static Builder|Session whereCreatedAt($value)
 * @method static Builder|Session whereDescription($value)
 * @method static Builder|Session whereId($value)
 * @method static Builder|Session whereName($value)
 * @method static Builder|Session whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Session extends SimpleModel
{
    use HasFactory;

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'session_id');
    }
}
