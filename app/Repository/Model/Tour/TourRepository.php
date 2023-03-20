<?php

namespace App\Repository\Model\Tour;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Flight\FlightInventoryTourUpgrade;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;
use App\Models\Tour\PaymentInstallment;
use App\Models\Tour\Tour;
use App\Models\Transport\TransportInventoryTour;
use App\Models\Transport\TransportInventoryTourUpgrade;
use App\Repository\Abstracts\ComponentPackageRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Costing\Tour\TourCostingRepository;
use App\Repository\Interfaces\HasStockControl;
use App\Repository\Interfaces\Manifest\HasActivityManifest;
use App\Repository\Interfaces\Manifest\HasFlightManifest;
use App\Repository\Interfaces\Manifest\HasRoomingList;
use App\Repository\Interfaces\Manifest\HasTransportManifest;
use App\Repository\Reporting\Manifest\ActivityManifestRepository;
use App\Repository\Reporting\Manifest\FlightManifestRepository;
use App\Repository\Reporting\Manifest\TransportManifestRepository;
use App\Repository\RoomingRepository;
use App\Repository\Storage\OrderComponentStorage;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Settings;

class TourRepository extends ComponentPackageRepository implements HasStockControl, HasRoomingList, HasActivityManifest, HasFlightManifest, HasTransportManifest
{
    private Tour $tour;
    private TourCostingRepository $costing;

    public function __construct(Tour $tour)
    {
        $this->tour = $tour;
        $this->costing = new TourCostingRepository($tour);
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
            if ($inventoryTourRepository->getTourComponentType() == 'Upgrade') continue;
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
     * @param bool $merchandise Should merchandise/extras be included
     * @param array $filter Filter for component types
     * @return InventoryTourRepository[]
     */
    public function getComponents(bool $accommodation = true, bool $activities = true, bool $flights = true, bool $transport = true, bool $merchandise = true, array $filter = ['Included', 'Add-on', 'Upgrade']): array
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
        if ($merchandise) {
            foreach ($this->tour->merchandise()->whereIn('tour_component_type', $filter)->get() as $inventoryTour) {
                $components[] = $inventoryTour->repository;
            }
        }
        return $components;
    }

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

    public function fulfilAll()
    {
        /** @var MerchandiseInventoryTour $merchandiseInventoryTour */
        foreach ($this->tour->merchandise()->with('orderComponents')->get() as $merchandiseInventoryTour) {
            foreach ($merchandiseInventoryTour->orderComponents as $orderComponent) {
                $orderComponent->repository->update(['fulfilled' => true,]);
            }
        }
    }

    public function addInstallment(Carbon $due, float $amount, bool $is_percentage = false): PaymentInstallment
    {
        $installment = PaymentInstallment::make([
            'due_on' => $due,
            'amount' => $amount,
            'is_percentage' => $is_percentage,
        ]);
        $this->tour->paymentInstallments()->save($installment);
        return $installment;
    }

    public function getRoomingList(): Collection|array
    {
        return $this->tour->orderAccommodation()->with(
            'group',
            'group.orderCustomers',
            'group.orderCustomers.customer',
            'accommodationInventoryTour',
            'accommodationInventoryTour.inventory',
            'accommodationInventoryTour.accommodationInventory.accommodation',
            'accommodationInventoryTour.accommodationInventory.roomType',
            'accommodationInventoryTour.accommodationInventory.boardType'
        )->get();
    }

    public function getPotentialRevenue(): float
    {
        return $this->tour->stock_control_active ? $this->tour->base_price_per_person * $this->tour->stock : -1;
    }

    public function getReceivedRevenue(): float
    {
        $total = 0;
        foreach ($this->tour->orders as $order) {
            $total += $order->paid;
        }
        return $total;
    }

    public function getRemainingRevenue(): float
    {
        $total = 0;
        foreach ($this->tour->orders as $order) {
            $total += $order->remaining;
        }
        return $total;
    }

    public function getCosting(): TourCostingRepository
    {
        return $this->costing;
    }

    /**
     * @return OrderActivity[]
     */
    public function getIncludedActivitiesForSaving(): array
    {
        $components = [];
        foreach ($this->tour->activityInventoryTours()->where('tour_component_type', '=', 'Included')->where('is_bookable', '=', true)->get() as $component) {
            $components[] = OrderActivity::make([
                'activity_inventory_tour_id' => $component->id,
                'cost' => $component->tour_sales_price,
            ]);
        }
        return $components;
    }

    /**
     * @return OrderFlight[]
     */
    public function getIncludedFlightsForSaving(): array
    {
        $components = [];
        foreach ($this->tour->flightInventoryTours()->where('tour_component_type', '=', 'Included')->where('is_bookable', '=', 1)->get() as $component) {
            $components[] = OrderFlight::make([
                'flight_inventory_tour_id' => $component->id,
                'cost' => $component->tour_sales_price,
            ]);
        }
        return $components;
    }

    /**
     * @return OrderTransport[]
     */
    public function getIncludedTransportForSaving(): array
    {
        $components = [];
        foreach ($this->tour->transportInventoryTours()->where('tour_component_type', '=', 'Included')->where('is_bookable', '=', 1)->get() as $component) {
            $components[] = OrderTransport::make([
                'transport_inventory_tour_id' => $component->id,
                'cost' => $component->tour_sales_price,
            ]);
        }
        return $components;
    }

    /**
     * @return OrderMerchandise[]
     */
    public function getIncludedMerchandiseForSaving(): array
    {
        $components = [];
        foreach ($this->tour->merchandise()->where('tour_component_type', '=', 'Included')->where('is_bookable', '=', 1)->get() as $component) {
            $components[] = OrderMerchandise::make([
                'merchandise_inventory_tour_id' => $component->id,
                'cost' => $component->tour_sales_price,
            ]);
        }
        return $components;
    }

    public function getComponentSetForSaving(): OrderComponentStorage
    {
        return new OrderComponentStorage(
          $this->getIncludedActivitiesForSaving(),
          $this->getIncludedFlightsForSaving(),
          $this->getIncludedTransportForSaving(),
        );
    }

    public function getActivityManifest(): Collection|array
    {
        return $this->tour->orderActivities()->with(ActivityManifestRepository::getRelations())->get();
    }

    public function getFlightManifest(): Collection|array
    {
        return $this->tour->orderFlights()->with(FlightManifestRepository::getRelations())->get();
    }

    public function getTransportManifest(): Collection|array
    {
        return $this->tour->orderTransport()->with(TransportManifestRepository::getRelations())->get();
    }

    public function isLocked(string $key): bool
    {
        return Settings::isLocked($key, $this->tour->date_from, $this->tour->date_to);
    }

    public function isPassportLocked(): bool
    {
        return $this->isLocked('passport');
    }

    public function isAccommodationLocked(): bool
    {
        return $this->isLocked('accommodation');
    }

    public function isActivityLocked(): bool
    {
        return $this->isLocked('activity');
    }

    public function isFlightLocked(): bool
    {
        return $this->isLocked('flight');
    }

    public function isTransportLocked(): bool
    {
        return $this->isLocked('transport');
    }
}
