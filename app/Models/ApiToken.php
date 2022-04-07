<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\ApiToken
 *
 * @property string $token
 * @property int $user_id
 * @property Carbon $expiry
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User $user
 * @method static Builder|ApiToken newModelQuery()
 * @method static Builder|ApiToken newQuery()
 * @method static QueryBuilder|ApiToken onlyTrashed()
 * @method static Builder|ApiToken query()
 * @method static Builder|ApiToken whereCreatedAt($value)
 * @method static Builder|ApiToken whereDeletedAt($value)
 * @method static Builder|ApiToken whereExpiry($value)
 * @method static Builder|ApiToken whereToken($value)
 * @method static Builder|ApiToken whereUpdatedAt($value)
 * @method static Builder|ApiToken whereUserId($value)
 * @method static QueryBuilder|ApiToken withTrashed()
 * @method static QueryBuilder|ApiToken withoutTrashed()
 * @mixin \Eloquent
 */
class ApiToken extends Model
{
    use HasFactory, SoftDeletes;

    public const DEFAULT_EXPIRY = 90;
    public const DEFAULT_LIMIT = 48;
    public $incrementing = false;
    protected $fillable = ['token', 'expiry'];
    protected $casts = ['expiry' => 'datetime'];
    protected $primaryKey = 'token';
    protected $keyType = 'string';

    public function hasExpired(): bool
    {
        return now()->isAfter($this->expiry);
    }

    public function invalidate()
    {
        $this->expiry = now()->addMinutes(-1);
        $this->save();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
