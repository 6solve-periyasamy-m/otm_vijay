<?php

namespace App\Repository\Model\Flight;

use App\Models\Flight\Flight;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Interfaces\Manifest\HasFlightManifest;
use App\Repository\Reporting\Manifest\FlightManifestRepository;
use Illuminate\Support\Collection;

class FlightRepository extends ModelRepository implements HasFlightManifest
{
    private Flight $flight;

    public function __construct(Flight $flight)
    {
        $this->flight = $flight;
    }

    public function get(): Flight
    {
        return $this->flight;
    }

    public function update(array $data): Flight
    {
        $this->flight->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->flight->save();
    }

    public function delete(): bool
    {
        return $this->flight->delete();
    }

    public function isDeleted(): bool
    {
        return $this->flight->trashed();
    }

    public function __toString(): string
    {
        return "{$this->flight->airline} ({$this->flight->departureAirport} to {$this->flight->arrivalAirport})";
    }

    public function getFlightManifest(): Collection|array
    {
        return $this->flight->orders()->with(FlightManifestRepository::getRelations())->get();
    }
}
