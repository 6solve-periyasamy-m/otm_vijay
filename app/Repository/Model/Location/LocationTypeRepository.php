<?php

namespace App\Repository\Model\Location;

use App\Models\Location\LocationType;
use App\Repository\Abstracts\AttributeRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read LocationType $model
 */
class LocationTypeRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Location Type';
    }

    /**
     * @param bool $trashed
     * @return LocationType[]|Collection
     */
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return LocationType::withTrashed()->withCount('addresses')->get();
        } else {
            return LocationType::withCount('addresses')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', LocationType::class)) {
            return route('location-types.create');
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', LocationType::class)) {
            return route('location-types.update', ['locationType' => $this->model,]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', LocationType::class) && $this->canDelete()) {
            return route('location-types.delete', ['locationType' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->addresses()->count();
    }
}
