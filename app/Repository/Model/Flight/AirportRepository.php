<?php

namespace App\Repository\Model\Flight;

use App\Models\Flight\Airport;
use App\Repository\Abstracts\AttributeRepository;
use App\Repository\Authentication\PermissionsRepository;
use Illuminate\Support\Collection;

/**
 * @property-read Airport $model
 */
class AirportRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Airport';
    }

    /**
     * @param bool $trashed
     * @return Airport[]|Collection
     */
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return Airport::withTrashed()->withCount(['departingFlights', 'arrivingFlights'])->get();
        } else {
            return Airport::withCount(['departingFlights', 'arrivingFlights'])->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('create', Airport::class)) {
            return route('airports.create');
        }
        return null;
    }

    public function getEditUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('update', Airport::class)) {
            return route('airports.update', ['airport' => $this->model,]);
        }
        return null;
    }

    public function getDeleteUrl(): string|null
    {
        if (PermissionsRepository::canCurrentUser('delete', Airport::class) && $this->canDelete()) {
            return route('airports.delete', ['airport' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->departingFlights()->count() + $this->model->arrivingFlights()->count();
    }

    public function __toString(): string
    {
        return "{$this->model->name} ({$this->model->iata_code}) - {$this->model->address->country}";
    }

    public static function find($id): Airport|null
    {
        return Airport::find($id);
    }
}
