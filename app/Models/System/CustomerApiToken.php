<?php

namespace App\Models\System;

use App\Models\Customer\Customer;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use function now;

/**
 * App\Models\CustomerApiToken
 *
 * @property string $token
 * @property int $customer_id
 * @property Carbon $expiry
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Customer $customer
 * @method static Builder|CustomerApiToken newModelQuery()
 * @method static Builder|CustomerApiToken newQuery()
 * @method static QueryBuilder|CustomerApiToken onlyTrashed()
 * @method static Builder|CustomerApiToken query()
 * @method static Builder|CustomerApiToken whereCreatedAt($value)
 * @method static Builder|CustomerApiToken whereCustomerId($value)
 * @method static Builder|CustomerApiToken whereDeletedAt($value)
 * @method static Builder|CustomerApiToken whereExpiry($value)
 * @method static Builder|CustomerApiToken whereToken($value)
 * @method static Builder|CustomerApiToken whereUpdatedAt($value)
 * @method static QueryBuilder|CustomerApiToken withTrashed()
 * @method static QueryBuilder|CustomerApiToken withoutTrashed()
 * @mixin Eloquent
 */
class CustomerApiToken extends Model
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
