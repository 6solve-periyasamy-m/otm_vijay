<?php

namespace App\Repository\Model\Tour;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Flight\FlightInventoryTourUpgrade;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventoryTour;
use App\Models\Transport\TransportInventoryTourUpgrade;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Interfaces\HasStockControl;
use App\Repository\RoomingRepository;

class TourRepository extends ModelRepository implements HasStockControl
{
    private Tour $tour;

    public function __construct(Tour $tour)
    {
        $this->tour = $tour;
    }

    public static function create(array $data): Tour
    {
        return Tour::create($data);
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

    public function get(): Tour
    {
        return $this->tour;
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

    public function getAvailableStock(): int
    {
        return $this->getTotalStock() - $this->getUsedStock();
    }

    public function getTotalStock(): int
    {
        return $this->tour->stock;
    }

    public function getUsedStock(): int
    {
        $used = 0;
        foreach ($this->tour->orders as $order) {
            if (!$order->cancelled) $used += $order->orderCustomers()->count();
        }
        return $used;
    }

    public function duplicate(): Tour
    {
        $newTour = $this->tour->replicate();
        $newTour->save();
        foreach ($this->getComponents(true, true, true, true, false, ['Included', 'Add-on']) as $inventoryTourRepository) {
            $inventoryTour = $inventoryTourRepository->get();
            if ($inventoryTourRepository->getComponentType() == 'Upgrade') continue;
            $newInventoryTour = $inventoryTour->replicate();
            $newInventoryTour->tour_id = $newTour->id;
            $newInventoryTour->save();
            foreach ($inventoryTour->upgrades as $upgrade) {
                $newUpgrade = $upgrade->replicate();
                $newUpgrade->base_id = $newInventoryTour->id;
                $clonedInventoryUpgrade = $upgrade->upgrade->replicate();
                $clonedInventoryUpgrade->tour_id = $newTour->id;
                $clonedInventoryUpgrade->save();
                $newUpgrade->upgrade_id = $clonedInventoryUpgrade->id;
                $newUpgrade->save();
            }
        }
        foreach ($this->tour->merchandise as $inventoryTour) {
            $newInventoryTour = $inventoryTour->replicate();
            $newInventoryTour->tour_id = $newTour->id;
            $newInventoryTour->save();
        }
        foreach ($this->tour->paymentInstallments as $inventoryTour) {
            $newInventoryTour = $inventoryTour->replicate();
            $newInventoryTour->tour_id = $newTour->id;
            $newInventoryTour->save();
        }
        return $newTour;
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

    /** @noinspection PhpMethodParametersCountMismatchInspection */
    public function fixUpgrades(): void
    {
        foreach ($this->tour->accommodationInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type !== 'Included') continue;
            $upgrades = AccommodationInventoryTour::join('accommodation_inventories', 'accommodation_inventory_tours.accommodation_inventory_id', '=', 'accommodation_inventories.id')
                ->where('accommodation_inventory_tours.tour_id', '=', $this->tour->id)
                ->where('accommodation_inventory_tours.tour_component_type', '=', 'Upgrade')
                ->where('accommodation_inventories.accommodation_id', '=', $inventoryTour->inventory->component->id)
                ->where('accommodation_inventories.check_in', '=', $inventoryTour->inventory->check_in)
                ->where('accommodation_inventories.room_type_id', '=', $inventoryTour->inventory->room_type_id)
                ->select('*', 'accommodation_inventory_tours.id as t_id')
                ->get();
            foreach ($upgrades as $component) {
                $existing = AccommodationInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->where('upgrade_id', '=', $component->t_id)->get();
                if (sizeof($existing) > 0) continue;
                $upgrade = AccommodationInventoryTourUpgrade::make([
                    'upgrade_id' => $component->t_id,
                    'description' => 'Upgrade to ' . $component->inventory->boardType,
                ]);
                $inventoryTour->upgrades()->save($upgrade);
                $upgrade->save();
            }
        }
        foreach ($this->tour->activityInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type !== 'Included') continue;
            $upgrades = ActivityInventoryTour::join('activity_inventories', 'activity_inventory_tours.activity_inventory_id', '=', 'activity_inventories.id')
                ->where('activity_inventory_tours.tour_id', '=', $this->tour->id)
                ->where('activity_inventory_tours.tour_component_type', '=', 'Upgrade')
                ->where('activity_inventories.activity_id', '=', $inventoryTour->inventory->component->id)
                ->where('activity_inventories.starts_at', '=', $inventoryTour->inventory->starts_at)
                ->select('*', 'activity_inventory_tours.id as t_id')
                ->get();
            foreach ($upgrades as $component) {
                $existing = ActivityInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->where('upgrade_id', '=', $component->t_id)->get();
                if (sizeof($existing) > 0) continue;
                $upgrade = ActivityInventoryTourUpgrade::make([
                    'upgrade_id' => $component->t_id,
                    'description' => 'Upgrade to ' . $component->inventory->ticketType,
                ]);
                $inventoryTour->upgrades()->save($upgrade);
                $upgrade->save();
            }
        }
        foreach ($this->tour->flightInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type !== 'Included') continue;
            $upgrades = FlightInventoryTour::join('flight_inventories', 'flight_inventory_tours.flight_inventory_id', '=', 'flight_inventories.id')
                ->where('flight_inventory_tours.tour_id', '=', $this->tour->id)
                ->where('flight_inventory_tours.tour_component_type', '=', 'Upgrade')
                ->where('flight_inventories.flight_id', '=', $inventoryTour->inventory->component->id)
                ->where('flight_inventories.departs_at', '=', $inventoryTour->inventory->check_in)
                ->select('*', 'flight_inventory_tours.id as t_id')
                ->get();
            foreach ($upgrades as $component) {
                $existing = FlightInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->where('upgrade_id', '=', $component->t_id)->get();
                if (sizeof($existing) > 0) continue;
                $upgrade = FlightInventoryTourUpgrade::make([
                    'upgrade_id' => $component->t_id,
                    'description' => 'Upgrade to ' . $component->inventory->travelClass,
                ]);
                $inventoryTour->upgrades()->save($upgrade);
                $upgrade->save();
            }
        }
        foreach ($this->tour->transportInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type !== 'Included') continue;
            $upgrades = TransportInventoryTour::join('transport_inventories', 'transport_inventory_tours.transport_inventory_id', '=', 'transport_inventories.id')
                ->where('transport_inventory_tours.tour_id', '=', $this->tour->id)
                ->where('transport_inventory_tours.tour_component_type', '=', 'Upgrade')
                ->where('transport_inventories.transport_id', '=', $inventoryTour->inventory->component->id)
                ->where('transport_inventories.departs_at', '=', $inventoryTour->inventory->departs_at)
                ->select('*', 'transport_inventory_tours.id as t_id')
                ->get();
            foreach ($upgrades as $component) {
                $existing = TransportInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->where('upgrade_id', '=', $component->t_id)->get();
                if (sizeof($existing) > 0) continue;
                $upgrade = TransportInventoryTourUpgrade::make([
                    'upgrade_id' => $component->t_id,
                    'description' => 'Upgrade to ' . $component->inventory->travelClass,
                ]);
                $inventoryTour->upgrades()->save($upgrade);
                $upgrade->save();
            }
        }
    }

    public function getTemplateData(): array
    {
        $data = [];
        foreach (RoomingRepository::getTemplateTourInventory($this->tour) as $template) {
            $templateData = ['template' => $template, 'available' => []];
            foreach (RoomingRepository::getHydratedRoomTypesForInventory($template) as $roomType) {
                $templateData['available'][] = $roomType;
            }
            $data[] = $templateData;
        }
        return $data;
    }

    public function autoAssignTemplating(): void
    {
        $dates = [];
        foreach ($this->tour->accommodationInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type !== 'Included') continue;
            $start = $inventoryTour->inventory->check_in->clone();
            $start->setTime(0, 0);
            if (array_key_exists($start->unix(), $dates)) {
                if ($inventoryTour->is_template && !$dates[$start->unix()]->is_template) {
                    $dates[$start->unix()] = $inventoryTour;
                }
            } else {
                $dates[$start->unix()] = $inventoryTour;
            }
        }
        foreach ($dates as $inventoryTour) {
            $inventoryTour->is_template = true;
            $inventoryTour->save();
        }
    }
}
