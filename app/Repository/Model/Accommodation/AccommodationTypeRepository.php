<?php

namespace App\Repository\Model\Accommodation;

use App\Models\Accommodation\AccommodationType;
use App\Repository\Abstracts\AttributeRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read AccommodationType $model
 */
class AccommodationTypeRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Accommodation Type';
    }

    /**
     * @param bool $trashed
     * @return AccommodationType[]|Collection
     */
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return AccommodationType::withTrashed()->withCount('accommodations')->get();
        } else {
            return AccommodationType::withCount('accommodations')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', AccommodationType::class)) {
            return route('accommodation-types.create');
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', AccommodationType::class)) {
            return route('accommodation-types.update', ['accommodationType' => $this->model,]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', AccommodationType::class) && $this->canDelete()) {
            return route('accommodation-types.delete', ['accommodationType' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->accommodations()->count();
    }

    public static function find($id): AccommodationType|null
    {
        return AccommodationType::find($id);
    }
}
