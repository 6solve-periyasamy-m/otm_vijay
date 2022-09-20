<?php

namespace App\Repository\Model\Transport;

use App\Models\Transport\TransportType;
use App\Repository\Abstracts\SmallModelRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read TransportType $model
 */
class TransportTypeRepository extends SmallModelRepository
{
    public static function getName(): string
    {
        return 'Transport Type';
    }

    /**
     * @param bool $trashed
     * @return TransportType[]|Collection
     */ 
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return TransportType::withTrashed()->withCount('transports')->get();
        } else {
            return TransportType::withCount('transports')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', TransportType::class)) {
            return route('transport-types.create');
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', TransportType::class)) {
            return route('transport-types.update', ['transportType' => $this->model,]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', TransportType::class) && $this->canDelete()) {
            return route('transport-types.delete', ['transportType' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->transports()->count();
    }
}
