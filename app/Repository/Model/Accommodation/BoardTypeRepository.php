<?php

namespace App\Repository\Model\Accommodation;

use App\Models\Accommodation\BoardType;
use App\Repository\Abstracts\AttributeRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read BoardType $model
 */
class BoardTypeRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Board Type';
    }

    /**
     * @param bool $trashed
     * @return BoardType[]|Collection
     */
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return BoardType::withTrashed()->withCount('inventories')->get();
        } else {
            return BoardType::withCount('inventories')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', BoardType::class)) {
            return route('board-types.create');
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', BoardType::class)) {
            return route('board-types.update', ['boardType' => $this->model,]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', BoardType::class) && $this->canDelete()) {
            return route('board-types.delete', ['boardType' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->inventories()->count();
    }

    public static function find($id): BoardType|null
    {
        return BoardType::find($id);
    }
}
