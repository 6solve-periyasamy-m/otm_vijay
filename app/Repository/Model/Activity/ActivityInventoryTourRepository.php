<?php

namespace App\Repository\Model\Activity;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Models\Activity\ActivityInventory;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Activity\ActivityInventoryTourUpgrade;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Booking\Component\BookingActivity;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\OrderCustomer;
use App\Models\Quote\Component\QuoteActivity;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Interfaces\Manifest\HasActivityManifest;
use App\Repository\Model\Order\Component\OrderActivityRepository;
use App\Repository\Model\Quote\Component\QuoteActivityRepository;
use App\Repository\Reporting\Manifest\ActivityManifestRepository;
use App\Repository\Storage\ComponentInformation;
use App\Repository\Traits\Component\IsActivity;
use Icon;
use Illuminate\Support\Collection;

class ActivityInventoryTourRepository extends InventoryTourRepository implements HasActivityManifest
{
    use IsActivity;

    private ActivityInventoryTour $tourComponent;

    public function __construct(ActivityInventoryTour $tourComponent)
    {
        $this->tourComponent = $tourComponent;
    }

    public static function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array
    {
        $components = [];
        foreach ($tour->activityInventoryTours as $component) {
            if ($component->tour_component_type !== "Upgrade") {
                if ($component->available_stock <= 0) continue;
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->activityInventory->activity->name;
                $components[$component->id]['activity_type'] = $component->activityInventory->activity->activityType->name;
            }
        }
        if ($orderCustomer != null) {
            // Remove all components the customer already has
            foreach ($orderCustomer->orderActivities as $oComponent) {
                $component = $oComponent->activityInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public function grantToCustomer(OrderCustomer $orderCustomer): ?OrderActivityRepository
    {
        $orderComponent = OrderActivity::create([
            'order_customer_id' => $orderCustomer->id,
            'activity_inventory_tour_id' => $this->tourComponent->id,
            'cost' => $this->tourComponent->tour_sales_price ?? 0,
        ]);
        event(new OrderCustomerComponentAddedEvent($orderComponent));
        return $orderComponent->repository;
    }

    public function getUpgradeParent(): ActivityInventoryTour
    {
        $upgrade = ActivityInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        if (!isset($upgrade)) return $this->tourComponent;
        return $upgrade->base;
    }

    public function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool
    {
        $upgrade = $upgradeRepository->get();
        if (!($upgrade instanceof ActivityInventoryTourUpgrade)) return false;
        if ($upgrade->base_id == $this->tourComponent->id) return true;
        foreach ($this->tourComponent->parent()->upgrades as $inventoryTourUpgrade) {
            if ($inventoryTourUpgrade->id == $upgrade->id) return true;
        }
        return false;
    }

    public function get(): ActivityInventoryTour
    {
        return $this->tourComponent;
    }

    public function update(array $data): ActivityInventoryTour
    {
        $this->tourComponent->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->tourComponent->save();
    }

    public function delete(): bool
    {
        return $this->tourComponent->delete();
    }

    public function isDeleted(): bool
    {
        return $this->tourComponent->trashed();
    }

    public function __toString(): string
    {
        $inventory = $this->tourComponent->activityInventory;
        $component = $inventory->activity;
        return $component->name . ' (' . f_datetime($inventory->starts_at) . ' to ' . f_datetime($inventory->ends_at) . ') (' . $inventory->ticketType->name . ')';
    }

    public function grantToBookingTraveller(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $active = $this->getActiveComponent($this, $traveller);
        if ($active !== null) return $active;
        $this->getActiveUpgrade($traveller)?->getBookingComponent($traveller)?->delete();
        $bookingComponent = BookingActivity::create([
            'booking_traveller_id' => $traveller->id,
            'activity_inventory_tour_id' => $this->tourComponent->id,
        ]);
        return $bookingComponent->repository;
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
        $component = $orderCustomer->orderActivities()->where('activity_inventory_tour_id', $this->tourComponent->id)->first();
        return $component?->repository;
    }

    public function getBookingComponent(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $component = $traveller->activities()->where('activity_inventory_tour_id', $this->tourComponent->id)->first();
        return $component?->repository;
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
     * @return Collection<ActivityInventory>
     */
    public function getAvailableForUpgrade(): Collection
    {
        $tour = $this->tourComponent->tour;
        return ActivityInventoryRepository::getBetweenDates($tour->date_from->setTime(0,0), $tour->date_to->setTime(23,59,59), $tour->repository);
    }

    public function getUpgradeId(): int
    {
        $upgrade = ActivityInventoryTourUpgrade::where('base_id', '=', $this->tourComponent->id)->first();
        if (isset($upgrade)) return 0;
        $upgrade = ActivityInventoryTourUpgrade::where('upgrade_id', '=', $this->tourComponent->id)->first();
        return isset($upgrade) ? $upgrade->id : -1;
    }

    public function isBookable(): bool
    {
        return $this->tourComponent->is_bookable;
    }

    public function getInventory(): ?ActivityInventoryRepository
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

    public function addToQuote(Quote $quote): ?QuoteActivityRepository
    {
        $component = QuoteActivity::create([
            'activity_inventory_id' => $this->tourComponent->activity_inventory_id,
            'quote_id' => $quote->id,
            'tour_component_type' => $this->tourComponent->tour_component_type,
            'tour_sales_price' => $this->tourComponent->tour_sales_price,
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

    public function getBookingUpgradeKeyMap(int $required = 1): array
    {
        $upgrades = $this->tourComponent->upgrades;
        $included = $this->tourComponent;
        $keys = [];
        if (empty($upgrades->all())) {
            $upgrades = $this->tourComponent->parent()->upgrades;
            $included = $this->tourComponent->parent();
        }
        $disabled = $included->available_stock <= $required - 1;
        if ($included->is_bookable) {
            $keys[0] = ['name' => 'Included - ' . ($disabled ? 'Out of Stock' : f_currency(0)), 'disabled' => $disabled,];
        }

        foreach ($upgrades as $upgrade) {
            if (!$upgrade->upgrade->is_bookable) continue;
            $disabled = $upgrade->upgrade->available_stock <= $required - 1;
            $keys[$upgrade->id] = ['name' => $upgrade->description . ' - ' . ($disabled ? 'Out of Stock' : f_currency($upgrade->upgrade->tour_sales_price)), 'disabled' => $disabled,];
        }
        return $keys;
    }

    public function getActivityManifest(): Collection|array
    {
        return $this->tourComponent->orders()->with(ActivityManifestRepository::getRelations())->get();
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
            $upgrade = ActivityInventoryTourUpgrade::where('upgrade_id', '=', $tourComponent->id)->first();
            $upgradeName = "$upgrade->description - " . f_currency($tourComponent->tour_sales_price);
        }
        return new ComponentInformation(
            $component->name,
            $component->description,
            $component->image_url,
            $inventory->starts_at,
            $inventory->ends_at,
            Icon::baseball(),
            $upgradeName,
            [
                'Starts At' => f_datetime($inventory->starts_at),
                'Ends At' => f_datetime($inventory->ends_at),
                'Ticket Type' => $inventory->ticketType,
            ]
        );
    }

    public function getStockUsedOnBooking(Booking $booking): int
    {
        return $booking->activities()->where('activity_inventory_tour_id', '=', $this->tourComponent->id)->count();
    }

    public function getCostToCustomer(): float
    {
        return $this->tourComponent->tour_component_type === 'Included' ? 0 : $this->tourComponent->tour_sales_price;
    }
}
