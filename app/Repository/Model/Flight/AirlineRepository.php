<?php

namespace App\Repository\Model\Flight;

use App\Models\Flight\Airline;
use App\Repository\Abstracts\AttributeRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read Airline $model
 */
class AirlineRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Airline';
    }

    /**
     * @param bool $trashed
     * @return Airline[]|Collection
     */
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return Airline::withTrashed()->withCount('flights')->get();
        } else {
            return Airline::withCount('flights')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', Airline::class)) {
            return route('airlines.create');
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', Airline::class)) {
            return route('airlines.update', ['airline' => $this->model,]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', Airline::class) && $this->canDelete()) {
            return route('airlines.delete', ['airline' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->flights()->count();
    }

    public static function find($id): Airline|null
    {
        return Airline::find($id);
    }
}
