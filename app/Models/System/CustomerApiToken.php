<?php

namespace App\Models\System;

use App\Models\Customer\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
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
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerApiToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerApiToken newQuery()
 * @method static \Illuminate\Database\Query\Builder|CustomerApiToken onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerApiToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerApiToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerApiToken whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerApiToken whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerApiToken whereExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerApiToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerApiToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|CustomerApiToken withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CustomerApiToken withoutTrashed()
 * @mixin \Eloquent
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
