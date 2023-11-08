<?php

namespace App\Repository\Model\Activity;

use App\Models\Activity\ActivityType;
use App\Repository\Abstracts\AttributeRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read ActivityType $model
 */
class ActivityTypeRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Activity Type';
    }

    /**
     * @param bool $trashed
     * @return ActivityType[]|Collection
     */
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return ActivityType::withTrashed()->withCount('activities')->get();
        } else {
            return ActivityType::withCount('activities')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', ActivityType::class)) {
            return route('activity-types.create');
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', ActivityType::class)) {
            return route('activity-types.update', ['activityType' => $this->model,]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', ActivityType::class) && $this->canDelete()) {
            return route('activity-types.delete', ['activityType' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->activities()->count();
    }

    public static function find($id): ActivityType|null
    {
        return ActivityType::find($id);
    }
}
