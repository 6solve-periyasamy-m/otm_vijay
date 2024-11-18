<?php

namespace App\Models\Customer;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer\Organization;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agent extends Model
{
    use HasFactory;
    use HasRelationships;

    protected $guarded = [];
    protected $primaryKey = 'id';

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Get all agents for the given organization
     *
     * @param Organization $organization
     * @return Collection
     */
    public function getAgentsFor(Organization $organization): Collection
    {
        return $this->where('organization_id', $organization->id)->get();
    }
}
