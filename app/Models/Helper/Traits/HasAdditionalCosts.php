<?php

namespace App\Models\Helper\Traits;

use App\Models\AdditionalCost;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property-read Collection|AdditionalCost[] $costs
 * @property-read int|null $costs_count
 */
trait HasAdditionalCosts
{
    public function costs(): MorphMany
    {
        return $this->morphMany(AdditionalCost::class, 'owner');
    }
}
