<?php

namespace App\Http\Controllers;

use App\Models\AccommodationInventoryTour;
use App\Models\AccommodationInventoryTourUpgrade;
use App\Models\ActivityInventoryTour;
use App\Models\ActivityInventoryTourUpgrade;
use App\Models\FlightInventoryTour;
use App\Models\FlightInventoryTourUpgrade;
use App\Models\Tour;
use App\Models\TransportInventoryTour;
use App\Models\TransportInventoryTourUpgrade;
use Illuminate\Http\Request;

class UpgradeController extends Controller
{
    public static function getValidationRules(string $table, bool $update = false) {
        if ($update) {
            return [
                'description' => 'required',
                'sales_price' => 'required|numeric'
            ];
        } else {
            return [
                'inventory_id' => 'required|exists:' . $table . ',id',
                'description' => 'required',
                'sales_price' => 'required|numeric'
            ];
        }

    }
    // Accommodation
    public function createAccommodationUpgrade(Tour $tour, AccommodationInventoryTour $inventoryTour) {
        return view('pages.upgrades.create', ['action' => route('accommodation-upgrade.store', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]),
            'name' => 'Accommodation Inventory', 'model' => 'accommodation', 'inventoryTour' => $inventoryTour,]);
    }

    public function storeAccommodationUpgrade(Request $request, Tour $tour, AccommodationInventoryTour $inventoryTour) {
        $request->validate(self::getValidationRules('accommodation_inventories'));
        $tourInventory = AccommodationInventoryTour::make([
            'tour_component_type' => 'Upgrade',
            'accommodation_inventory_id' => $request->input('inventory_id'),
            'tour_sales_price' => $request->input('sales_price'),
        ]);
        $tour->accommodationInventoryTours()->save($tourInventory);
        $upgrade = AccommodationInventoryTourUpgrade::make([
            'upgrade_id' => $tourInventory->id,
            'description' => $request->input('description'),
        ]);
        $inventoryTour->upgrades()->save($upgrade);
        return redirect()->route('accommodation-upgrade.view', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }

    public function viewAccommodationUpgrade(Tour $tour, AccommodationInventoryTour $inventoryTour) {
        return view('pages.upgrades.view.accommodation', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }
    
    public function editAccommodationUpgrade(Tour $tour, AccommodationInventoryTour $inventoryTour, AccommodationInventoryTourUpgrade $upgrade) {
        return view('pages.upgrades.update', ['action' => route('accommodation-upgrade.update', ['tour' => $tour, 'inventoryTour' => $inventoryTour,'upgrade'=>$upgrade,]),
            'name' => 'Accommodation Inventory']);
    }
    
    public function updateAccommodationUpgrade(Request $request, Tour $tour, AccommodationInventoryTour $inventoryTour, AccommodationInventoryTourUpgrade $upgrade) {
        $upgrade->upgrade->update([
            'tour_sales_price' => $request->input('sales_price'),
        ]);
        $upgrade->update([
            'description' => $request->input('description'),
        ]);
        $upgrade->upgrade->save();
        $upgrade->save();
        return redirect()->route('accommodation-upgrade.view', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }

    // Activity
    public function createActivityUpgrade(Tour $tour, ActivityInventoryTour $inventoryTour) {
        return view('pages.upgrades.create', ['action' => route('activity-upgrade.store', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]),
            'name' => 'Activity Inventory', 'model' => 'activity', 'inventoryTour' => $inventoryTour,]);
    }

    public function storeActivityUpgrade(Request $request, Tour $tour, ActivityInventoryTour $inventoryTour) {
        $request->validate(self::getValidationRules('activity_inventories'));
        $tourInventory = ActivityInventoryTour::make([
            'tour_component_type' => 'Upgrade',
            'activity_inventory_id' => $request->input('inventory_id'),
            'tour_sales_price' => $request->input('sales_price'),
        ]);
        $tour->activityInventoryTours()->save($tourInventory);
        $upgrade = ActivityInventoryTourUpgrade::make([
            'upgrade_id' => $tourInventory->id,
            'description' => $request->input('description'),
        ]);
        $inventoryTour->upgrades()->save($upgrade);
        return redirect()->route('activity-upgrade.view', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }

    public function viewActivityUpgrade(Tour $tour, ActivityInventoryTour $inventoryTour) {
        return view('pages.upgrades.view.activity', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }

    public function editActivityUpgrade(Tour $tour, ActivityInventoryTour $inventoryTour, ActivityInventoryTourUpgrade $upgrade) {
        return view('pages.upgrades.update', ['action' => route('activity-upgrade.update', ['tour' => $tour, 'inventoryTour' => $inventoryTour,'upgrade'=>$upgrade,]),
            'name' => 'Activity Inventory']);
    }

    public function updateActivityUpgrade(Request $request, Tour $tour, ActivityInventoryTour $inventoryTour, ActivityInventoryTourUpgrade $upgrade) {
        $upgrade->upgrade->update([
            'tour_sales_price' => $request->input('sales_price'),
        ]);
        $upgrade->update([
            'description' => $request->input('description'),
        ]);
        $upgrade->upgrade->save();
        $upgrade->save();
        return redirect()->route('activity-upgrade.view', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }

    // Flight
    public function createFlightUpgrade(Tour $tour, FlightInventoryTour $inventoryTour) {
        return view('pages.upgrades.create', ['action' => route('flight-upgrade.store', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]),
            'name' => 'Flight Inventory', 'model' => 'flight', 'inventoryTour' => $inventoryTour,]);
    }

    public function storeFlightUpgrade(Request $request, Tour $tour, FlightInventoryTour $inventoryTour) {
        $request->validate(self::getValidationRules('flight_inventories'));
        $tourInventory = FlightInventoryTour::make([
            'tour_component_type' => 'Upgrade',
            'flight_inventory_id' => $request->input('inventory_id'),
            'tour_sales_price' => $request->input('sales_price'),
        ]);
        $tour->flightInventoryTours()->save($tourInventory);
        $upgrade = FlightInventoryTourUpgrade::make([
            'upgrade_id' => $tourInventory->id,
            'description' => $request->input('description'),
        ]);
        $inventoryTour->upgrades()->save($upgrade);
        return redirect()->route('flight-upgrade.view', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }

    public function viewFlightUpgrade(Tour $tour, FlightInventoryTour $inventoryTour) {
        return view('pages.upgrades.view.flight', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }

    public function editFlightUpgrade(Tour $tour, FlightInventoryTour $inventoryTour, FlightInventoryTourUpgrade $upgrade) {
        return view('pages.upgrades.update', ['action' => route('flight-upgrade.update', ['tour' => $tour, 'inventoryTour' => $inventoryTour,'upgrade'=>$upgrade,]),
            'name' => 'Flight Inventory']);
    }

    public function updateFlightUpgrade(Request $request, Tour $tour, FlightInventoryTour $inventoryTour, FlightInventoryTourUpgrade $upgrade) {
        $upgrade->upgrade->update([
            'tour_sales_price' => $request->input('sales_price'),
        ]);
        $upgrade->update([
            'description' => $request->input('description'),
        ]);
        $upgrade->upgrade->save();
        $upgrade->save();
        return redirect()->route('flight-upgrade.view', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }

    // Transport
    public function createTransportUpgrade(Tour $tour, TransportInventoryTour $inventoryTour) {
        return view('pages.upgrades.create', ['action' => route('transport-upgrade.store', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]),
            'name' => 'Transport Inventory', 'model' => 'transport', 'inventoryTour' => $inventoryTour,]);
    }

    public function storeTransportUpgrade(Request $request, Tour $tour, TransportInventoryTour $inventoryTour) {
        $request->validate(self::getValidationRules('transport_inventories'));
        $tourInventory = TransportInventoryTour::make([
            'tour_component_type' => 'Upgrade',
            'transport_inventory_id' => $request->input('inventory_id'),
            'tour_sales_price' => $request->input('sales_price'),
        ]);
        $tour->transportInventoryTours()->save($tourInventory);
        $upgrade = TransportInventoryTourUpgrade::make([
            'upgrade_id' => $tourInventory->id,
            'description' => $request->input('description'),
        ]);
        $inventoryTour->upgrades()->save($upgrade);
        return redirect()->route('transport-upgrade.view', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }

    public function viewTransportUpgrade(Tour $tour, TransportInventoryTour $inventoryTour) {
        return view('pages.upgrades.view.transport', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }

    public function editTransportUpgrade(Tour $tour, TransportInventoryTour $inventoryTour, TransportInventoryTourUpgrade $upgrade) {
        return view('pages.upgrades.update', ['action' => route('transport-upgrade.update', ['tour' => $tour, 'inventoryTour' => $inventoryTour,'upgrade'=>$upgrade,]),
            'name' => 'Transport Inventory']);
    }

    public function updateTransportUpgrade(Request $request, Tour $tour, TransportInventoryTour $inventoryTour, TransportInventoryTourUpgrade $upgrade) {
        $upgrade->upgrade->update([
            'tour_sales_price' => $request->input('sales_price'),
        ]);
        $upgrade->update([
            'description' => $request->input('description'),
        ]);
        $upgrade->upgrade->save();
        $upgrade->save();
        return redirect()->route('transport-upgrade.view', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
    }
}
