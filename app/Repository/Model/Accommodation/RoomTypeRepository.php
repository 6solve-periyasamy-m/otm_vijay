<?php

namespace App\Repository\Model\Accommodation;

use App\Models\Accommodation\RoomType;
use App\Repository\Abstracts\AttributeRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read RoomType $model
 */
class RoomTypeRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Room Type';
    }

    /**
     * @param bool $trashed
     * @return RoomType[]|Collection
     */
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return RoomType::withTrashed()->withCount('inventories')->get();
        } else {
            return RoomType::withCount('inventories')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', RoomType::class)) {
            return route('room-types.create');
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', RoomType::class)) {
            return route('room-types.update', ['roomType' => $this->model,]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', RoomType::class) && $this->canDelete()) {
            return route('room-types.delete', ['roomType' => $this->model,]);
        }
        return null;
    }

    public function __toString(): string
    {
        return "{$this->model->name} (Occupancy {$this->model->maximum_occupancy})";
    }

    public function getRelatedCount(): int
    {
        return $this->model->inventories()->count();
    }

    public static function find($id): RoomType|null
    {
        return RoomType::find($id);
    }
}
