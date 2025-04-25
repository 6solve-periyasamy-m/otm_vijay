<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace App\Repository\Model\Tour;

use App\Exceptions\CannotDeleteException;
use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Models\Accommodation\BoardType;
use App\Models\Accommodation\RoomCategory;
use App\Models\Accommodation\RoomType;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Booking\Component\BookingActivity;
use App\Models\Booking\Component\BookingFlight;
use App\Models\Booking\Component\BookingMerchandise;
use App\Models\Booking\Component\BookingTransport;
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
use App\Repository\Abstracts\InventoryRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Costing\Tour\TourCostingRepository;
use App\Repository\Interfaces\HasStockControl;
use App\Repository\Interfaces\Manifest\HasActivityManifest;
use App\Repository\Interfaces\Manifest\HasFlightManifest;
use App\Repository\Interfaces\Manifest\HasMerchandiseManifest;
use App\Repository\Interfaces\Manifest\HasRoomingList;
use App\Repository\Interfaces\Manifest\HasTransportManifest;
use App\Repository\Model\Accommodation\AccommodationInventoryRepository;
use App\Repository\Model\Activity\ActivityInventoryRepository;
use App\Repository\Model\Merchandise\MerchandiseInventoryRepository;
use App\Repository\Model\Transport\TransportInventoryRepository;
use App\Repository\Reporting\Manifest\ActivityManifestRepository;
use App\Repository\Reporting\Manifest\FlightManifestRepository;
use App\Repository\Reporting\Manifest\MerchandiseManifestRepository;
use App\Repository\Reporting\Manifest\TransportManifestRepository;
use App\Repository\RoomingRepository;
use App\Repository\Storage\BookingComponentStorage;
use App\Repository\Storage\OrderComponentStorage;
use App\Repository\Storage\Tour\GroupedHotelRooming;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Settings;
use Illuminate\Support\Str;

class TourRepository extends ComponentPackageRepository implements HasStockControl, HasRoomingList, HasActivityManifest, HasFlightManifest, HasTransportManifest, HasMerchandiseManifest
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

    /**
     * @throws CannotDeleteException
     */
    public function delete(): bool
    {
        if ($this->tour->orders()->count() > 0) {
            throw new CannotDeleteException('This tour has orders and cannot be deleted.');
        }
        if ($this->tour->bookings()->count() > 0) {
            throw new CannotDeleteException('This tour has bookings and cannot be deleted.');
        }
        foreach ($this->tour->accommodationInventoryTours as $model) { $model->repository->delete(); }
        foreach ($this->tour->activityInventoryTours as $model) { $model->repository->delete(); }
        foreach ($this->tour->flightInventoryTours as $model) { $model->repository->delete(); }
        foreach ($this->tour->transportInventoryTours as $model) { $model->repository->delete(); }
        foreach ($this->tour->merchandise as $model) { $model->repository->delete(); }
        foreach ($this->tour->costs as $model) { $model->forceDelete(); }
        foreach ($this->tour->paymentInstallments as $model) { $model->forceDelete(); }
        $this->tour->voucherPivot()->delete();
        return $this->tour->delete();
    }

    public function isDeleted(): bool
    {
        return $this->tour->trashed();
    }

    public function __toString(): string
    {
        return $this->tour->name;
    }

    public function getAvailableStock(): int
    {
        return $this->getTotalStock() - $this->getUsedStock();
    }

    public function getTotalStock(): int
    {
        return $this->tour->stock ?? 0;
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
        $newTour->name = "{$newTour->name} ({$this->tour->id} Duplicate)";
        $newTour->booking_form_url = null;
        $newTour->is_active = false;
        $newTour->save();
        foreach ($this->getComponents(true, true, true, true, false, ['Included', 'Add-on']) as $inventoryTourRepository) {
            $inventoryTour = $inventoryTourRepository->get();
            if ($inventoryTourRepository->getTourComponentType() === 'Upgrade') continue;
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
                ->select(['*', 'accommodation_inventory_tours.id as t_id'])
                ->get();
            foreach ($upgrades as $component) {
                $existing = AccommodationInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->where('upgrade_id', '=', $component->t_id)->get();
                if (count($existing) > 0) continue;
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
                ->select(['*', 'activity_inventory_tours.id as t_id'])
                ->get();
            foreach ($upgrades as $component) {
                $existing = ActivityInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->where('upgrade_id', '=', $component->t_id)->get();
                if (count($existing) > 0) continue;
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
                ->select(['*', 'flight_inventory_tours.id as t_id'])
                ->get();
            foreach ($upgrades as $component) {
                $existing = FlightInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->where('upgrade_id', '=', $component->t_id)->get();
                if (count($existing) > 0) continue;
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
                ->select(['*', 'transport_inventory_tours.id as t_id'])
                ->get();
            foreach ($upgrades as $component) {
                $existing = TransportInventoryTourUpgrade::where('base_id', '=', $inventoryTour->id)->where('upgrade_id', '=', $component->t_id)->get();
                if (count($existing) > 0) continue;
                $upgrade = TransportInventoryTourUpgrade::make([
                    'upgrade_id' => $component->t_id,
                    'description' => 'Upgrade to ' . $component->inventory->travelClass,
                ]);
                $inventoryTour->upgrades()->save($upgrade);
                $upgrade->save();
            }
        }
    }

    /**
     * @return array<int, array{template: AccommodationInventoryTour, available: AccommodationInventoryTour[]}>
     */
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

    public function fulfilAll(): void
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
                'cost' => $component->tour_sales_price ?? 0.0,
                'estimated_purchase_price' => $component->inventory->local_purchase_price,
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
                'cost' => $component->tour_sales_price ?? 0.0,
                'estimated_purchase_price' => $component->inventory->local_purchase_price,
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
                'cost' => $component->tour_sales_price ?? 0.0,
                'estimated_purchase_price' => $component->inventory->local_purchase_price,
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
                'cost' => $component->tour_sales_price ?? 0.0,
                'estimated_purchase_price' => $component->inventory->local_purchase_price,
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
          $this->getIncludedMerchandiseForSaving(),
        );
    }


    /**
     * @return BookingActivity[]
     */
    public function getBookingIncludedActivitiesForSaving(): array
    {
        $components = [];
        foreach ($this->tour->activityInventoryTours()->where('tour_component_type', '=', 'Included')->where('is_bookable', '=', true)->get() as $component) {
            $components[] = BookingActivity::make([
                'activity_inventory_tour_id' => $component->id,
            ]);
        }
        return $components;
    }

    /**
     * @return BookingFlight[]
     */
    public function getBookingIncludedFlightsForSaving(): array
    {
        $components = [];
        foreach ($this->tour->flightInventoryTours()->where('tour_component_type', '=', 'Included')->where('is_bookable', '=', 1)->get() as $component) {
            $components[] = BookingFlight::make([
                'flight_inventory_tour_id' => $component->id,
            ]);
        }
        return $components;
    }

    /**
     * @return BookingTransport[]
     */
    public function getBookingIncludedTransportForSaving(): array
    {
        $components = [];
        foreach ($this->tour->transportInventoryTours()->where('tour_component_type', '=', 'Included')->where('is_bookable', '=', 1)->get() as $component) {
            $components[] = BookingTransport::make([
                'transport_inventory_tour_id' => $component->id,
            ]);
        }
        return $components;
    }

    /**
     * @return BookingMerchandise[]
     */
    public function getBookingIncludedMerchandiseForSaving(): array
    {
        $components = [];
        foreach ($this->tour->merchandise()->where('tour_component_type', '=', 'Included')->where('is_bookable', '=', 1)->get() as $component) {
            $components[] = BookingMerchandise::make([
                'merchandise_inventory_tour_id' => $component->id,
            ]);
        }
        return $components;
    }

    public function getBookingComponentSetForSaving(): BookingComponentStorage
    {
        return new BookingComponentStorage(
          $this->getBookingIncludedActivitiesForSaving(),
          $this->getBookingIncludedFlightsForSaving(),
          $this->getBookingIncludedTransportForSaving(),
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

    public function getMerchandiseManifest(): Collection|array
    {
        return $this->tour->orderMerchandise()->with(MerchandiseManifestRepository::getRelations())->get();
    }

    public function isStockControlActive(): bool
    {
        return $this->tour->stock_control_active;
    }

    public function hasEnoughStock(int $amount = 1): bool
    {
        return !($this->isStockControlActive() && $this->getAvailableStock() < $amount);
    }

    public function isLocked(string $key): bool
    {
        return Settings::isLocked($key, $this->tour->date_from, $this->tour->date_to);
    }

    public function isPassportLocked(): bool
    {
        return $this->isLocked('passport');
    }

    public function isOrderNotesLocked(): bool
    {
        return $this->isLocked('order-notes');
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

    public function isComponentsLocked(): bool
    {
        return $this->tour->date_from->subDays(setting("components.lock", 30))->lte(now());
    }

    public static function find($id): Tour|null
    {
        return Tour::find($id);
    }

    public function cloneFromDefaultInstallments(): void
    {
        if ($this->tour->deposit === null) {
            $this->tour->deposit = setting('system.installments.deposit');
            $this->tour->is_deposit_percentage = true;
            $this->tour->save();
        }
        $installments = Settings::getDefaultInstallments();
        if (count($installments) > 0) {
            foreach($installments as $days => $percentage) {
                $this->tour->paymentInstallments()->save(new PaymentInstallment(['due_on' => $this->tour->date_from->subDays($days), 'amount' => $percentage, 'is_percentage' => true,]));
            }
        }
    }

    public function getInclusions(int $limit = -1): array
    {
        /** @var InventoryRepository[] $components */
        $components = [];
        $seen = [];
        foreach ($this->tour->accommodationInventoryTours as $component) {
            $key = 'accommodation-' .  $component->inventory->component->id;
            if (in_array($key, $seen)) { continue; }
            $seen[] = $key;
            if ($component->tour_component_type === 'Included') {
                $components[] = $component->inventory->repository;
            }
        }
        foreach ($this->tour->activityInventoryTours as $component) {
            $key = "activity-{$component->activity_inventory_id}";
            if (in_array($key, $seen)) { continue; }
            $seen[] = $key;
            if ($component->tour_component_type === 'Included') {
                $components[] = $component->inventory->repository;
            }
        }
        foreach ($this->tour->merchandise as $component) {
            $key = 'merchandise-' .  $component->inventory->component->id;
            if (in_array($key, $seen)) { continue; }
            $seen[] = $key;
            if ($component->tour_component_type === 'Included') {
                $components[] = $component->inventory->repository;
            }
        }
        foreach ($this->tour->transportInventoryTours as $component) {
            $key = 'transport-' .  $component->inventory->component->id;
            if (in_array($key, $seen)) { continue; }
            $seen[] = $key;
            if ($component->tour_component_type === 'Included') {
                $components[] = $component->inventory->repository;
            }
        }
        usort($components, static function (InventoryRepository $a, InventoryRepository $b) {
            return $a->getStartTime()?->unix() <=> $b->getStartTime()?->unix();
        });
        $inclusions = [];
        foreach ($components as $component) {
            if ($limit === 0) {
                break;
            }
            $inclusion = match (true) {
                $component instanceof AccommodationInventoryRepository =>
                $component->getStartTime()?->format('d M') . " - " . $component->get()->accommodation->name . ' - ' . $component->getNightsInTour($this->tour) . ' Nights',
                $component instanceof ActivityInventoryRepository =>
                    $component->getStartTime()?->format('d M') . " - " . $component->get()->activity->name,
                $component instanceof MerchandiseInventoryRepository =>
                    $component->get()->component->name,
                $component instanceof TransportInventoryRepository =>
                    $component->get()->component->name,
                default => null,
            };
            // TODO: Implement Flights
            if ($inclusion !== null) {
                $inclusions[] = $inclusion;
                $limit--;
            }
        }
        return $inclusions;
    }

    /**
     * @return array<int, array{hotel: Accommodation, type: string}>
     */
    public function getHotels(): array
    {
        // TODO: Optimize
        $hotels = [];
        foreach ($this->tour->accommodationInventory()->groupBy('accommodation_id')->get() as $inventory) {
            $hotels[$inventory->accommodation_id] = ['hotel' => $inventory->accommodation, 'type' => $inventory->category?->name, 'board' => $inventory->boardType?->name];
        }
        return $hotels;
    }

    public function getRooms(Accommodation|int|null $hotel = null): array
    {
        $rooms = [];
        if (is_int($hotel)) {
            $hotel = Accommodation::find($hotel);
        }
        if ($hotel !== null) {
            $tourComponents =
                $this->tour->accommodationInventoryTours()
                    ->join('accommodation_inventories', 'accommodation_inventories.id', '=', 'accommodation_inventory_tours.accommodation_inventory_id')
                    ->where('accommodation_inventories.accommodation_id', '=', $hotel->id)
                    ->get();
        } else {
            $tourComponents = $this->tour->accommodationInventoryTours;
        }
        foreach ($tourComponents as $inventoryTour) {
            $name = $inventoryTour->inventory->roomType->name;
            if ($inventoryTour->tour_component_type !== 'Included') {
                $cost = $inventoryTour->tour_sales_price;
                if ($cost > 0) {
                    $name .= ' (+' . f_currency($cost) . ')';
                }
                if ($cost < 0) {
                    $name .= ' (-' . f_currency($cost*-1) . ')';
                }
            }
            $rooms[$inventoryTour->inventory->room_type_id] = $name;
        }
        return $rooms;
    }

    public function getBookingRooms(Accommodation|int|null $hotel = null): array
    {
        $rooms = [];
        if (is_int($hotel)) {
            $hotel = Accommodation::find($hotel);
        }
        if ($hotel !== null) {
            $tourComponents =
                $this->tour->accommodationInventoryTours()
                    ->join('accommodation_inventories', 'accommodation_inventories.id', '=', 'accommodation_inventory_tours.accommodation_inventory_id')
                    ->where('accommodation_inventories.accommodation_id', '=', $hotel->id)
                    ->get();
        } else {
            $tourComponents = $this->tour->accommodationInventoryTours;
        }
        foreach ($tourComponents as $inventoryTour) {
            $name = $inventoryTour->inventory->roomType->name;
            if ($inventoryTour->tour_component_type !== 'Included') {
                $cost = $inventoryTour->tour_sales_price;
                if ($cost > 0) {
                    $name .= ' (+' . f_currency($cost) . ')';
                }
                if ($cost < 0) {
                    $name .= ' (-' . f_currency($cost*-1) . ')';
                }
            }

            $bedType = trim(Str::afterLast($name, '-'));
            $rooms[$inventoryTour->inventory->room_type_id] = ['id'=> $inventoryTour->inventory->roomType->id, 'name' => $bedType, 'room_desc' => $inventoryTour->inventory->category_description, 'occupancy' => $inventoryTour->inventory->roomType->maximum_occupancy];
        }
        return $rooms;
    }

    public function getDefaultRoom(int|null $hotel = null): int|null
    {
        foreach ($this->getRooms($hotel) as $key => $name) {
            return $key;
        }
        return null;
    }

    public function getDataForBooking(): array
    {
        return [
            'name' => $this->tour->name,
            'event' => [
                'name' => $this->tour->event?->name,
                'description' => $this->tour->event?->description,
                'image' => $this->tour->event?->image_url !== null ? asset($this->tour->event?->image_url) : null,
            ],
            'start' => $this->tour->date_from,
            'end' => $this->tour->date_to,
            'description' => $this->tour->description,
            'image' => $this->tour->event?->image_url !== null ? asset($this->tour->event?->image_url) : null,
            'inclusions' => $this->getInclusions(),
            'rooms' => $this->getRooms(),
        ];
    }

    /**
     * Get a list of GroupedHotelRooming, grouped into arrays based on hotel ID
     *
     * @return array<int, GroupedHotelRooming[]>
     */
    public function getHotelGroups(): array
    {
        /** @var array<int, GroupedHotelRooming[]> $hotelGroups */
        $hotelGroups = [];
        foreach ($this->tour->accommodationInventoryTours as $inventoryTour) {
            $found = false;
            $inventory = $inventoryTour->inventory;
            if (array_key_exists($inventory->accommodation_id, $hotelGroups)) {
                foreach ($hotelGroups[$inventory->accommodation_id] as $key => $hotelGroup) {
                    if ($hotelGroup->add($inventoryTour)) {
                        $found = true;
                        $hotelGroups[$inventoryTour->accommodation_id][$key] = $hotelGroup;
                        break;
                    }
                }
            }
            if (!$found) {
                $hotelGroups[$inventoryTour->accommodation_id] = [GroupedHotelRooming::fromInventoryTour($inventoryTour),];
            }
        }
        return $hotelGroups;
    }

    /**
     * Get a flattened list of GroupedHotelRooming, not grouped by hotel id
     *
     * @return GroupedHotelRooming[]
     */
    public function getFlattenedGroups(): array
    {
        $array = [];
        foreach ($this->getHotelGroups() as $key => $hotelGroups) {
            $array = [...$array, ...$hotelGroups];
        }
        return $array;
    }

    public function getHotelGroup(Accommodation $hotel, RoomType $roomType, BoardType $boardType, RoomCategory|null $category): GroupedHotelRooming|null
    {
        foreach (($this->getHotelGroups()[$hotel->id] ?? []) as $hotelGroup) {
            if ($hotelGroup->occupancy->id === $roomType->id
                && $hotelGroup->board->id === $boardType->id
                && $hotelGroup->category?->id === $category?->id) {
                return $hotelGroup;
            }
        }
        return null;
    }
}
