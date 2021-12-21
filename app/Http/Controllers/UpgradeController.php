<?php

namespace App\Http\Controllers;

use App\Models\AccommodationInventoryTour;
use App\Models\AccommodationInventoryTourUpgrade;
use App\Models\ActivityInventoryTour;
use App\Models\ActivityInventoryTourUpgrade;
use App\Models\Tour;
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

}
