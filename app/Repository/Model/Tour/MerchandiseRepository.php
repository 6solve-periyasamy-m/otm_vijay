<?php

namespace App\Repository\Model\Tour;

use App\Models\Booking\BookingTraveller;
use App\Models\Booking\Component\BookingMerchandise;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Merchandise;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Abstracts\ComponentUpgradeRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Interfaces\HasStockControl;
use DB;

class MerchandiseRepository extends InventoryTourRepository implements HasStockControl
{
    private Merchandise $merchandise;

    public function __construct(Merchandise $merchandise)
    {
        $this->merchandise = $merchandise;
    }

    public static function getAvailableAddons(Tour $tour, OrderCustomer $orderCustomer = null): array
    {
        $components = [];
        foreach ($tour->merchandise as $component) {
            if ($component->available_stock <= 0) continue;
            $components[$component->id] = [];
            $components[$component->id]['id'] = $component->id;
            $components[$component->id]['name'] = $component->name;
        }
        if ($orderCustomer != null) {
            // Remove all components the customer already has
            foreach ($orderCustomer->orderMerchandise as $oComponent) {
                $component = $oComponent->merchandise;
                unset($components[$component->id]);
            }
        }
        return $components;
    }

    public function grantToCustomer(OrderCustomer $orderCustomer): ?OrderComponentRepository
    {
        return OrderMerchandise::create([
            'order_customer_id' => $orderCustomer->id,
            'merchandise_id' => $this->id,
            'cost' => $this->tour_sales_price,
        ]);
    }

    public function getUpgradeParent(): Merchandise
    {
        return $this->merchandise; // Merchandise do not have upgrades
    }

    public function onUpgradeTree(ComponentUpgradeRepository $upgradeRepository): bool
    {
        return false; // Merchandise do not have upgrades
    }

    public function get(): Merchandise
    {
        return $this->merchandise;
    }

    public function update(array $data): Merchandise
    {
        $this->merchandise->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->merchandise->save();
    }

    public function delete(): bool
    {
        return $this->merchandise->delete();
    }

    public function isDeleted(): bool
    {
        return $this->merchandise->trashed();
    }

    public function __toString(): string
    {
        return $this->merchandise->name;
    }

    public function getUsedStock(): int
    {
        $query = DB::table('order_merchandises');
        $query->join('merchandises', 'order_merchandises.merchandise_id', '=', 'merchandises.id');
        $query->join('order_customers', 'order_merchandises.order_customer_id', '=', 'order_customers.id');
        $query->join('orders', 'order_customers.order_id', '=', 'orders.id');
        $query->where('merchandises.id', '=', $this->merchandise->id);
        $query->where('orders.cancelled', '=', 0);
        $query->whereNull('order_merchandises.deleted_at');
        return $query->selectRaw("count(order_merchandises.id) as 'used_stock'")->first()->used_stock;
    }

    public function getTotalStock(): int
    {
        return $this->merchandise->stock;
    }

    public function getAvailableStock(): int
    {
        return $this->getTotalStock() - $this->getUsedStock();
    }

    public function grantToTraveller(BookingTraveller $traveller): ?BookingComponentRepository
    {
        \Log::info($traveller);
        $bookingComponent = BookingMerchandise::create([
            'booking_traveller_id' => $traveller->id,
            'merchandise_id' => $this->merchandise->id,
        ]);
        return $bookingComponent->repository;
    }

    public function getOrderComponent(OrderCustomer $orderCustomer): ?OrderComponentRepository
    {
        $component = $orderCustomer->orderMerchandise()->where('merchandise_id', $this->merchandise->id)->first();
        return $component?->repository;
    }

    public function getBookingComponent(BookingTraveller $traveller): ?BookingComponentRepository
    {
        $component = $traveller->merchandise()->where('merchandise_id', $this->merchandise->id)->first();
        return $component?->repository;
    }

    public function getComponentString(): string
    {
        return 'extra';
    }

    public function getCost(): float
    {
        return $this->merchandise->tour_sales_price;
    }

    public function getComponentType(): string
    {
        return $this->merchandise->tour_component_type;
    }
}