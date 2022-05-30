<?php

namespace App\Repository;

//use App\Events\Order\Customer\Component\Accommodation\OrderCustomerAccommodationAddedEvent;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\AccommodationInventoryTourUpgrade;
use App\Models\Accommodation\RoomType;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AccommodationComponentRepository
{

    public static function getAvailableAddons($tourId, $oCustomerId = -1): array
    {
        $tour = Tour::findOrFail($tourId);
        $oCustomer = $oCustomerId == -1 ? null : OrderCustomer::findOrFail($oCustomerId);
        $components = [];
        foreach ($tour->accommodationInventoryTours as $component) {
            if ($component->tour_component_type !== "Upgrade") {
                if ($component->available_stock <= 0) continue;
                $components[$component->id] = [];
                $components[$component->id]['id'] = $component->id;
                $components[$component->id]['name'] = $component->accommodationInventory->accommodation->name;
                $components[$component->id]['room_type'] = $component->accommodationInventory->roomType->name;
            }
        }
        if ($oCustomer != null) {
            // Remove all components the customer already has
            foreach ($oCustomer->orderAccommodation as $oComponent) {
                $component = $oComponent->accommodationInventoryTour;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public static function getParentComponent(AccommodationInventoryTour $inventoryTour): AccommodationInventoryTour
    {
        $upgrade = AccommodationInventoryTourUpgrade::where('upgrade_id', '=', $inventoryTour->id)->first();
        if (!isset($upgrade)) return $inventoryTour;
        return $upgrade->base;
    }

    public static function isOnUpgradeTree(AccommodationInventoryTour $inventoryTour, AccommodationInventoryTourUpgrade $upgrade): bool
    {
        if ($upgrade->base_id == $inventoryTour->id) return true;
        foreach ($inventoryTour->parent()->upgrades as $inventoryTourUpgrade) {
            if ($inventoryTourUpgrade->id == $upgrade->id) return true;
        }
        return false;
    }
}
