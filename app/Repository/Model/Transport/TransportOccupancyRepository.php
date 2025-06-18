<?php

namespace App\Repository\Model\Transport;

use App\Models\Transport\TransportOccupancy;
use App\Repository\Abstracts\AttributeRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read TransportOccupancy $model
 */
class TransportOccupancyRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Transport Occupancy';
    }

    /**
     * Get all transport occupancies
     *
     * @param bool $trashed
     * @return TransportOccupancy[]|Collection
     */
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return TransportOccupancy::withTrashed()->withCount('inventories')->get();
        } else {
            return TransportOccupancy::withCount('inventories')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', TransportOccupancy::class)) {
            return route('occupancy.create');
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', TransportOccupancy::class)) {
            return route('occupancy.update', ['occupancy' => $this->model]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', TransportOccupancy::class) && $this->canDelete()) {
            return route('occupancy.delete', ['occupancy' => $this->model]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->inventories()->count();
    }

    public static function find($id): TransportOccupancy|null
    {
        return TransportOccupancy::find($id);
    }
}