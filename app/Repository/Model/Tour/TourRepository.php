<?php

namespace App\Repository\Model\Tour;

use App\Models\Tour\Tour;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\ModelRepository;

class TourRepository extends ModelRepository
{
    private Tour $tour;

    public function __construct(Tour $tour)
    {
        $this->tour = $tour;
    }

    /**
     * @param bool $accommodation Should accommodation be included
     * @param bool $activities Should activities be included
     * @param bool $flights Should flights be included
     * @param bool $transport Should transport be included
     * @param bool $extras Should merchandise/extras be included
     * @param array $filter Filter for component types
     * @return InventoryTourRepository[]
     */
    public function getComponents(bool $accommodation = true, bool $activities = true, bool $flights = true, bool $transport = true, bool $extras = true, array $filter = ['Included', 'Add-on', 'Upgrade']): array
    {
        $components = [];
        if ($accommodation) {
            foreach ($this->tour->accommodationInventoryTours()->whereIn('tour_component_type', $filter)->get() as $inventoryTour) {
                $components[] = $inventoryTour->repository;
            }
        }
        if ($activities) {
            foreach ($this->tour->activityInventoryTours()->whereIn('tour_component_type', $filter)->get() as $inventoryTour) {
                $components[] = $inventoryTour->repository;
            }
        }
        if ($flights) {
            foreach ($this->tour->flightInventoryTours()->whereIn('tour_component_type', $filter)->get() as $inventoryTour) {
                $components[] = $inventoryTour->repository;
            }
        }
        if ($transport) {
            foreach ($this->tour->transportInventoryTours()->whereIn('tour_component_type', $filter)->get() as $inventoryTour) {
                $components[] = $inventoryTour->repository;
            }
        }
        if ($extras) {
            foreach ($this->tour->merchandise()->whereIn('tour_component_type', $filter)->get() as $inventoryTour) {
                $components[] = $inventoryTour->repository;
            }
        }
        return $components;
    }

    public function get(): Tour
    {
        return $this->tour;
    }

    public function update(array $data): Tour
    {
        $this->tour->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->tour->save();
    }

    public function delete(): bool
    {
        return $this->tour->delete();
    }

    public function isDeleted(): bool
    {
        return $this->tour->trashed();
    }

    public function __toString(): string
    {
        return "{$this->tour->name}";
    }
}