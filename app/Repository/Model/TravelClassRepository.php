<?php

namespace App\Repository\Model;

use App\Models\TravelClass;
use App\Repository\Abstracts\AttributeRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read TravelClass $model
 */
class TravelClassRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Travel Classes';
    }

    /**
     * @param bool $trashed
     * @return TravelClass[]|Collection
     */ 
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return TravelClass::withTrashed()->withCount(['transportInventories', 'flightInventories'])->get();
        } else {
            return TravelClass::withCount(['transportInventories', 'flightInventories'])->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', TravelClass::class)) {
            return route('travel-classes.create');
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', TravelClass::class)) {
            return route('travel-classes.update', ['travelClass' => $this->model,]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', TravelClass::class) && $this->canDelete()) {
            return route('travel-classes.delete', ['travelClass' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->transportInventories()->count() + $this->model->flightInventories()->count();
    }
}
