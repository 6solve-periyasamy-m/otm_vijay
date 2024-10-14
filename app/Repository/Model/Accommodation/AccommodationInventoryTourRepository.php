<?php

namespace App\Repository\Model\Accommodation;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Booking\Component\BookingAccommodation;
use App\Models\Customer\Group;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\OrderCustomer;
use App\Models\Quote\Component\QuoteAccommodation;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Model\Quote\Component\QuoteAccommodationRepository;
use App\Repository\Storage\ComponentInformation;
use App\Repository\Storage\Itinerary\ItineraryItem;
use App\Repository\Traits\Component\IsAccommodation;
use Auth;
use Icon;
use Illuminate\Support\Collection;

class AccommodationInventoryTourRepository extends InventoryTourRepository
{
    use IsAccommodation;

    private AccommodationInventoryTour $tourComponent;

    public function __construct(AccommodationInventoryTour $tourComponent)
    {
        $this->tourComponent = $tourComponent;
    }

    public static function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array
    {
        $components = [];
        foreach ($tour->accommodationInventoryTours()
                     ->with('accommodationInventory', 'accommodationInventory.accommodation')
                     ->get() as $component) {
            if ($component->tour_component_type !== "Upgrade") {
                if ($component->available_stock <= 0) continue;
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->accommodationInventory->accommodation->name;
                $components[$component->id]['room_type'] = $component->accommodationInventory->roomType->name;
            }
        }
        if ($orderCustomer != null) {
            // Remove all components the customer already has
            foreach ($orderCustomer->orderAccommodation as $oComponent) {
                $component = $oComponent->accommodationInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public function get(): AccommodationInventoryTour
    {
        return $this->tourComponent;
    }

    public function grantToCustomer(OrderCustomer $orderCustomer, bool $silent = false): ?OrderComponentRepository
    {
        $orderComponent = $this->getOrderComponent($orderCustomer);
        if ($orderComponent !== null) return $orderComponent;
        $group = $orderCustomer->primary_group;
        if (!isset($group)) {
            $group = Group::create();
            $group->repository->addCustomerToGroup($orderCustomer);
        }
        $component = $group->repository->addRoomToGroup($this->tourComponent, $silent);
        //event(new OrderCustomerAccommodationAddedEvent($orderComponent));
        return $component->repository;
    }

    public function getUpgradeParent(): AccommodationInventoryTour
    {
        $upgrade = AccommodationInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        if (!isset($upgrade)) return $this->tourComponent;
        return $upgrade->base;
    }

    /**
     * @param ComponentUpgradeRepository $upgradeRepository Expected AccommodationInventoryTourUpgradeRepository
     * @return boolAccommodationInventoryTourCon
     */
    public function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool
    {
        $upgrade = $upgradeRepository->get();
        if (!($upgrade instanceof AccommodationInventoryTourUpgrade)) return false;
        if ($upgrade->base_id == $this->tourComponent->id) return true;
        foreach ($this->tourComponent->parent()->upgrades as $inventoryTourUpgrade) {
            if ($inventoryTourUpgrade->id == $upgrade->id) return true;
        }
        return false;
    }

    public function update(array $data): AccommodationInventoryTour
    {
        $this->tourComponent->update($data);
        $this->save();
        return $this->tourComponent;
    }

    public function save(): bool
    {
        return $this->tourComponent->save();
    }

    public function delete(): bool
    {
        if ($this->canDelete()) {
            // Will eventually move away from soft-deletes. TODO: Switch to delete when occurs
            return $this->tourComponent->forceDelete();
        }
        return false;
    }

    public function isDeleted(): bool
    {
        return $this->tourComponent->id === null || $this->tourComponent->trashed();
    }

    public function __toString(): string
    {
        $inventory = $this->tourComponent->accommodationInventory;
        $component = $inventory->accommodation;
        return $component->name . ' (' . f_datetime($inventory->check_in) . ' to ' . f_datetime($inventory->check_out) . ') (' . $inventory->roomType->name . ', ' . $inventory->boardType->name . ')';
    }

    public function formatAdminOccupancy(): string
    {
        $inventory = $this->tourComponent->accommodationInventory;
        $component = $inventory->accommodation;
        return $component->name . ' (' . f_datetime($inventory->check_in) . ' to ' . f_datetime($inventory->check_out) . ') (' . $inventory->roomType->name . ' (' . $inventory->roomType->maximum_occupancy . '), ' . $inventory->boardType->name . ')';
    }

    public function grantToBookingTraveller(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $active = $this->getActiveComponent($this, $traveller);
        if ($active !== null) return $active;
        $this->getActiveUpgrade($traveller)?->getBookingComponent($traveller)?->delete();
        $component = BookingAccommodation::create([
            'booking_group_id' => $traveller->primary_group->id,
            'accommodation_inventory_tour_id' => $this->tourComponent->id,
        ]);
        return $component->repository;
    }

    public function getUsedStock(): int
    {
        return $this->tourComponent->inventory->repository->getUsedStock();
    }

    public function getTotalStock(): int
    {
        return $this->tourComponent->inventory->repository->getTotalStock();
    }

    public function getAvailableStock(): int
    {
        return $this->tourComponent->inventory->repository->getAvailableStock();
    }

    public function getOrderComponent(OrderCustomer $orderCustomer): ?OrderComponentRepository
    {
        foreach ($orderCustomer->orderAccommodation()->get() as $accommodation) {
            if ($accommodation->accommodation_inventory_tour_id == $this->tourComponent->id) return $accommodation->repository;
        }
        return null;
    }

    public function getBookingComponent(BookingTraveller $traveller): ?BookingComponentRepository
    {
        foreach ($traveller->accommodation as $accommodation) {
            if ($accommodation->accommodation_inventory_tour_id == $this->tourComponent->id) return $accommodation->repository;
        }
        return null;
    }

    public function getCost(): float
    {
        return $this->tourComponent->tour_sales_price;
    }

    public function getTourComponentType(): string
    {
        return $this->tourComponent->tour_component_type;
    }

    /**
     * @return Collection<AccommodationInventory>
     */
    public function getAvailableForUpgrade(): Collection
    {
        $tour = $this->tourComponent->tour;
        return AccommodationInventoryRepository::getBetweenDates($tour->date_from->setTime(0,0), $tour->date_to->setTime(23,59,59), $tour->repository);
    }

    public function getUpgradeId(): int
    {
        $upgrade = AccommodationInventoryTourUpgrade::where('base_id', '=', $this->tourComponent->id)->first();
        if (isset($upgrade)) return 0;
        $upgrade = AccommodationInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        return isset($upgrade) ? $upgrade->id : -1;
    }

    public function isBookable(): bool
    {
        return $this->tourComponent->is_bookable;
    }

    public function getInventory(): ?AccommodationInventoryRepository
    {
        return $this->tourComponent->inventory->repository;
    }

    public function getUsedOnOrderCount(): int
    {
        $used = 0;
        foreach ($this->tourComponent->orders as $orderComponent) {
            if (!$orderComponent->cancelled) $used++;
        }
        return $used;
    }

    public function addToQuote(Quote $quote): ?QuoteAccommodationRepository
    {
        $component = QuoteAccommodation::create([
            'accommodation_inventory_id' => $this->tourComponent->accommodation_inventory_id,
            'quote_id' => $quote->id,
            'tour_component_type' => $this->tourComponent->tour_component_type,
            'tour_sales_price' => $this->tourComponent->tour_sales_price,
            'is_template' => $this->tourComponent->is_template,
        ]);
        return $component->repository;
    }

    public function isStockControlActive(): bool
    {
        return $this->tourComponent->stock_control_active ?? false;
    }

    public function hasEnoughStock(int $amount = 1): bool
    {
        return !($this->isStockControlActive() && $this->getAvailableStock() < $amount);
    }

    public function getComponentInformation(): ComponentInformation
    {
        $tourComponent = $this->tourComponent;
        $inventory = $tourComponent->inventory;
        $component = $inventory->component;
        if ($tourComponent->tour_component_type === 'Add-on') {
            $upgradeName = "Add-on";
        } elseif ($tourComponent->tour_component_type === 'Included') {
            $upgradeName = "Included";
        } else {
            $upgrade = AccommodationInventoryTourUpgrade::where('upgrade_id', '=', $tourComponent->id)->first();
            $upgradeName = "$upgrade->description - " . f_currency($tourComponent->tour_sales_price);
        }
        return new ComponentInformation(
            $component->name,
            $component->description,
            $component->image_url,
            $inventory->check_in,
            $inventory->check_out,
            Icon::accommodation(),
            $inventory->external_notes,
            $upgradeName,
            [
                'Check In' => f_datetime($inventory->check_in),
                'Check Out' => f_datetime($inventory->check_out),
                'Board Type' => $inventory->boardType->name,
                'Room Type' => $inventory->roomType->__toString()
            ]
        );
    }

    public function getActiveUpgrade(BookingTraveller|OrderCustomer $traveller): AccommodationInventoryTourRepository|null
    {
        /**
         * @var OrderAccommodation[]|BookingAccommodation[] $accommodation
         */
        $accommodation = ($traveller instanceof OrderCustomer) ? $traveller->orderAccommodation : $traveller->accommodation;
        $parent = $this->getUpgradeParent();
        $upgrades = $parent->upgrades;
        foreach ($accommodation as $room) {
            if ($room->accommodation_inventory_tour_id === $parent->id) return $parent->repository;
            foreach ($upgrades as $upgrade) {
                if ($room->accommodation_inventory_tour_id === $upgrade->upgrade_id) return $upgrade->upgrade->repository;
            }
        }
        return null;
    }

    public function getStockUsedOnBooking(Booking $booking): int
    {
        return $booking->accommodation()->where('accommodation_inventory_tour_id', '=', $this->tourComponent->id)->count();
    }

    public function getCostToCustomer(): float
    {
        return $this->tourComponent->tour_component_type === 'Included' ? 0 : $this->tourComponent->tour_sales_price;
    }

    public static function find($id): AccommodationInventoryTour|null
    {
        return AccommodationInventoryTour::find($id);
    }

    public function getItineraryItem(int|null $quantity = null): ItineraryItem
    {
        return $this->getInventory()?->getItineraryItem($quantity);
    }

    public function getOverview(): string
    {
        return $this->tourComponent->inventory->component->name . ' - ' . $this->tourComponent->inventory->roomType . ' - ' . $this->tourComponent->inventory->boardType;
    }

    public function getUpdateLink(): string|null
    {
        if (Auth::user()?->can('update', $this->tourComponent)) {
            return route('accommodation-inventory-tours.edit', [
                'tour' => $this->tourComponent->tour_id,
                'inventoryTour' => $this->tourComponent
            ]);
        }
        return null;
    }

    public function getDeleteLink(): string|null
    {
        if (Auth::user()?->can('delete', $this->tourComponent)) {
            return route('accommodation-inventory-tours.delete', [
                'tour' => $this->tourComponent->tour_id,
                'inventoryTour' => $this->tourComponent
            ]);
        }
        return null;
    }

    public function getRestoreLink(): string|null
    {
        if (Auth::user()?->can('delete', $this->tourComponent)) {
            return route('accommodation-inventory-tours.restore', [
                'tour' => $this->tourComponent->tour_id,
                'inventoryTour' => $this->tourComponent
            ]);
        }
        return null;
    }

    public function getOrderedCount(): int
    {
        return $this->tourComponent->orders()->count();
    }

    public function getBookedCount(): int
    {
        return $this->tourComponent->bookings()->count();
    }
}
