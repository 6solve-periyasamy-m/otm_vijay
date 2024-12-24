<?php

namespace App\Models\Customer;

use App\Models\Order\Order;
use App\Models\Quote\Quote;
use Carbon\Carbon;
use Database\Factories\Customer\AgentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Agent
 *
 * @package App\Models\Customer
 * @property int $id The unique identifier for the user.
 * @property string $first_name The first name of the agent.
 * @property string $last_name The last name of the agent.
 * @property string $email The email address of the agent.
 * @property int|null $organization_id The id of the organization the agent is related to.
 * @property Carbon $created_at The timestamp when the user was created.
 * @property Carbon $updated_at The timestamp when the user was last updated.
 * @property-read Collection<int, Order> $orders
 * @property-read int|null $orders_count
 * @property-read Organization|null $organization
 * @property-read Collection<int, Quote> $quotes
 * @property-read int|null $quotes_count
 * @method static AgentFactory factory($count = null, $state = [])
 * @method static Builder|Agent newModelQuery()
 * @method static Builder|Agent newQuery()
 * @method static Builder|Agent query()
 * @method static Builder|Agent whereCreatedAt($value)
 * @method static Builder|Agent whereEmail($value)
 * @method static Builder|Agent whereFirstName($value)
 * @method static Builder|Agent whereId($value)
 * @method static Builder|Agent whereLastName($value)
 * @method static Builder|Agent whereOrganizationId($value)
 * @method static Builder|Agent whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class Agent extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the organization that the agent belongs to.
     *
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Get the agent has quotes
     *
     * @return HasMany
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class, 'agent_id');
    }

    /**
     * Get the agent has orders
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'agent_id');
    }

    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
