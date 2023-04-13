<?php

namespace App\Repository\Model\Booking;

use App\Models\Activity\ActivityInventoryTour;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Location\Address;
use App\Models\Location\AddressParent;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Repository\Abstracts\BookingComponentRepository;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Model\Flight\FlightInventoryTourRepository;
use App\Repository\Storage\BookingComponentStorage;
use App\Repository\Storage\Customer\Component\BookingComponent;
use DB;
use Log;
use Throwable;

class BookingTravellerRepository extends ModelRepository
{
    private BookingTraveller $traveller;

    public function __construct(BookingTraveller $traveller)
    {
        $this->traveller = $traveller;
    }

    public function getSelectedFlights(): array
    {
        $selected = ['outbound' => 0, 'inbound' => 0,];
        foreach ($this->traveller->flights as $flight) {
            if ($flight->tourComponent->flight_type == 'Outbound') {
                $selected['outbound'] = $flight->flight_inventory_tour_id;
            } else if ($flight->tourComponent->flight_type == 'Inbound') {
                $selected['inbound'] = $flight->flight_inventory_tour_id;
            }
        }
        return $selected;
    }

    public function selectFlights(?FlightInventoryTour $inbound, ?FlightInventoryTour $outbound): void
    {
        $inbound?->repository->grantToBookingTraveller($this->traveller);
        $outbound?->repository->grantToBookingTraveller($this->traveller);
    }

    /**
     * @param InventoryTourRepository[] $tourComponentRepositories
     * @return void
     */
    public function addComponents(array $tourComponentRepositories): void
    {
        foreach ($tourComponentRepositories as $tourComponentRepository) {
            $this->addComponent($tourComponentRepository);
        }
    }

    public function addComponent(InventoryTourRepository $tourComponentRepository): bool
    {
        $travellers = $this->traveller->booking->travellers()->count();
        if (!$tourComponentRepository->hasEnoughStock($travellers)) {
            $found = false;
            foreach ($tourComponentRepository->get()->upgrades()->with('upgrade')->get() as $upgrade) {
                $repo = $upgrade->upgrade->repository;
                if ($repo->hasEnoughStock($travellers)) {
                    $tourComponentRepository = $repo;
                    $found = true;
                    break;
                }
            }
            if (!$found) return false;
        }
        $component = $tourComponentRepository->grantToBookingTraveller($this->traveller);
        return isset($component);
    }

    public function get(): BookingTraveller
    {
        return $this->traveller;
    }

    public function upgradeActivity(ActivityInventoryTour $from, ActivityInventoryTour $to, bool $verified = false): bool
    {
        if (!$verified) {
            if (!$to->repository->hasEnoughStock()) return false;
            if (!$to->is_bookable) return false;
        }
        try {
            if (!$verified) {
                DB::beginTransaction();
            }
            DB::table('booking_activities')
                ->where('booking_traveller_id', '=', $this->traveller->id)
                ->where('activity_inventory_tour_id', '=', $from->id)
                ->update(['activity_inventory_tour_id' => $to->id,]);
            if (!$verified) {
                DB::commit();
            }
        } catch (Throwable $e) {
            Log::error($e);
            if (!$verified) {
                DB::rollBack();
            }
            return false;
        }
        return true;
    }

    public function update(array $data): BookingTraveller
    {
        $this->traveller->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->traveller->save();
    }

    /**
     * @return InventoryTourRepository[]
     */
    public function getAvailableAddons(bool $filter = false): array
    {
        $tour = $this->traveller->booking->tour;
        $components = $tour->repository->getComponents(false, true, false, false, false, ['Add-on',]);
        $available = [];
        if (!$filter) {
            return $components;
        }
        foreach ($components as $inventoryTourRepository) {
            $repo = $inventoryTourRepository->getBookingComponent($this->traveller);
            if (isset($repo)) continue;
            $available[] = $inventoryTourRepository;
        }
        return $available;
    }

    /**
     * @return BookingComponentRepository[]
     */
    public function getComponents(bool $includeAccommodation = true, array $typeFilters = ['Included', 'Upgrade', 'Add-on']): array
    {
        $components = [];
        if ($includeAccommodation) {
            foreach ($this->traveller->accommodation()->with('tourComponent')->get() as $orderComponent) {
                if (!isset($orderComponent->tourComponent) || !in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
                $components[] = $orderComponent->repository;
            }
        }
        foreach ($this->traveller->activities()->with('tourComponent')->get() as $orderComponent) {
            if (!isset($orderComponent->tourComponent) || !in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
            $components[] = $orderComponent->repository;
        }
        foreach ($this->traveller->flights()->with('tourComponent')->get() as $orderComponent) {
            if (!isset($orderComponent->tourComponent) || !in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
            $components[] = $orderComponent->repository;
        }
        foreach ($this->traveller->transport()->with('tourComponent')->get() as $orderComponent) {
            if (!isset($orderComponent->tourComponent) || !in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
            $components[] = $orderComponent->repository;
        }
        foreach ($this->traveller->merchandise()->with('tourComponent')->get() as $orderComponent) {
            if (!isset($orderComponent->tourComponent) || !in_array($orderComponent->tourComponent->tour_component_type, $typeFilters)) continue;
            $components[] = $orderComponent->repository;
        }
        return $components;
    }

    public function convertToOrderCustomer(Order $order): OrderCustomer
    {
        if (!isset($this->traveller->customer_id)) {
            $this->convertToCustomer();
        }
        $orderCustomer = OrderCustomer::make([
            'customer_id' => $this->traveller->customer_id,
            'tour_cost' => $this->traveller->booking->tour->base_price_per_person,
            'single_occupancy_surcharge' => $this->traveller->booking->tour->single_occupancy_surcharge,
        ]);
        $order->orderCustomers()->save($orderCustomer);
        foreach ($this->getComponents(false) as $componentRepository) {
            $componentRepository->getTourComponent()->grantToCustomer($orderCustomer);
        }
        $this->traveller->order_customer_id = $orderCustomer->id;
        $this->save();
        return $orderCustomer;
    }

    public function convertToCustomer(): Customer
    {
        if (isset($this->traveller->customer_id)) return $this->traveller->customer;
        $customer = Customer::create([
            'title' => $this->traveller->title ?? null,
            'first_name' => $this->traveller->first_name ?? null,
            'middle_names' => $this->traveller->middle_names ?? null,
            'last_name' => $this->traveller->last_name ?? null,
            'email_address' => $this->traveller->email_address ?? null,
            'date_of_birth' => $this->traveller->date_of_birth ?? null,
            'mobile_number' => $this->traveller->mobile_number ?? null,
            'home_address_id' => $this->traveller->home_address_id,
            'billing_address_id' => $this->traveller->billing_address_id,
        ]);
        $this->traveller->customer_id = $customer->id;
        return $customer;
    }

    /**
     * @param Booking $booking
     * @param array $details
     * @return BookingTraveller
     */
    public static function create(Booking $booking, array $details): BookingTraveller
    {
        $traveller = BookingTravellerRepository::make($details);
        $booking->travellers()->save($traveller);
        return $traveller;
    }

    public function formSave(int $roomType, int $group)
    {
        $this->traveller->room_type_id = $roomType;
        $this->traveller->group_id = $group;
        if (isset($this->traveller->id)) {
            $this->traveller->save();
            return;
        }
        $this->traveller->save();
        $this->saveComponentSet($this->traveller->booking->tour->repository->getBookingComponentSetForSaving());
        $this->traveller->booking->repository->evaluateSimpleRooming();
    }

    public function saveComponentSet(BookingComponentStorage $components)
    {
        $this->traveller->activities()->saveMany($components->activities);
        $this->traveller->flights()->saveMany($components->flights);
        $this->traveller->transport()->saveMany($components->transport);
    }

    public static function make(array $details): BookingTraveller
    {
        $customer = array_key_exists('email_address', $details) && !empty($details['email_address'])
            ? Customer::whereEmailAddress($details['email_address'])->first() : null;
        if (isset($customer)) {
            $homeAddress = $customer->homeAddress;
            $billingAddress = $customer->billingAddress;
        } else {
            $homeAddress = Address::create([
                'name' => ($details['first_name'] ?? '') . ($details['last_name'] ?? '') . ' - Home Address',
                'address_parent_id' => AddressParent::getParentId('customer'),
                'address_line_1' => $details['home_address_line_1'] ?? null,
                'address_line_2' => $details['home_address_line_2'] ?? null,
                'town' => $details['home_town'] ?? null,
                'region' => $details['home_region'] ?? null,
                'country_id' => $details['home_country_id'] ?? null,
                'postcode' => $details['home_postcode'] ?? null,
            ]);
            $billingAddress = Address::create([
                'name' => ($details['first_name'] ?? '') . ($details['last_name'] ?? '') . ' - Billing Address',
                'address_parent_id' => AddressParent::getParentId('customer'),
                'address_line_1' => $details['billing_address_line_1'] ?? null,
                'address_line_2' => $details['billing_address_line_2'] ?? null,
                'town' => $details['billing_town'] ?? null,
                'region' => $details['billing_region'] ?? null,
                'country_id' => $details['billing_country_id'] ?? null,
                'postcode' => $details['billing_postcode'] ?? null,
            ]);
        }
        return BookingTraveller::make([
            'customer_id' => $customer?->id,
            'title' => $details['title'] ?? null,
            'first_name' => $details['first_name'] ?? null,
            'middle_names' => $details['middle_names'] ?? null,
            'last_name' => $details['last_name'] ?? null,
            'email_address' => $details['email_address'] ?? null,
            'date_of_birth' => $details['date_of_birth'] ?? null,
            'mobile_number' => $details['mobile_number'] ?? null,
            'home_address_id' => $homeAddress->id,
            'billing_address_id' => $billingAddress->id,
            'room_type_id' => $details['room_type_id'],
            'group_id' => $details['group_id'],
        ]);
    }

    public function getTotalCost(): float
    {
        return $this->getBaseCost() + $this->getAdditionalCost() + $this->getSingleOccupancy();
    }

    public function getBaseCost(): float
    {
        return $this->traveller->booking->tour->base_price_per_person;
    }

    public function getAdditionalCost(): float
    {
        $cost = 0;
        foreach ($this->getComponents(true, ['Upgrade', 'Add-on']) as $componentRepository) {
            $cost += $componentRepository->getCost();
        }
        return $cost;
    }

    public function getSingleOccupancy(): float
    {
        return $this->hasSingleOccupancy() ? $this->traveller->booking->tour->single_occupancy_surcharge : 0;
    }

    public function hasSingleOccupancy(): bool
    {
        foreach ($this->traveller->groups as $group) {
            if ($group->travellers()->count() == 1) return true;
        }
        return false;
    }

    public function delete(): bool
    {
        foreach ($this->traveller->groups as $group) {
            if ($group->travellers()->count() == 1) {
                $group->delete();
            }
        }
        return $this->traveller->delete();
    }

    public function isDeleted(): bool
    {
        return !isset($this->traveller);
    }

    public function __toString(): string
    {
        return "{$this->traveller->first_name} {$this->traveller->last_name} - {$this->traveller?->booking?->token}";
    }

    /**
     * @return array<int, BookingComponent[]> Set of booking components, grouped by date
     */
    public function getSummaryComponents(): array
    {
        /**
         * @var BookingComponent[] $components
         */
        $components = [];
        foreach ($this->traveller->booking->tour->repository->getComponents(false, true, true, true, false, ['Included', 'Add-on']) as $component) {
            $active = $component->getActiveUpgrade($this->traveller);
            if ($active !== null) {
                $components[] = $active->getAbstractBookingComponent($this->traveller);
            } else {
                if ($component instanceof FlightInventoryTourRepository) {
                    if ($component->get()->flight_type !== 'Mid-Package' && $component->getBookingComponent($this->traveller) === null) continue;
                }
                $components[] = $component->getAbstractBookingComponent($this->traveller);
            }
        }
        foreach ($this->traveller->accommodation as $accommodation) {
            $components[] = $accommodation->tourComponent->repository->getAbstractBookingComponent($this->traveller);
        }
        uasort($components, ['static', 'compareStarts']);
        $ordered = [];
        foreach ($components as $component) {
            $day = $component->start->clone()->setTime(0,0);
            if (!array_key_exists($day->unix(), $ordered)) {
                $ordered[$day->unix()] = [];
            }
            $ordered[$day->unix()][] = $component;
        }
        foreach ($ordered as $key => $values) {
            uasort($values, ['static', 'compareComponents']);
            $ordered[$key] = $values;
        }
        return $ordered;
    }

    private static function compareStarts(BookingComponent $a, BookingComponent $b): int
    {
        if ($a->start->eq($b->start)) return 0;
        return $a->start->lt($b->start) ? -1 : 1;
    }

    private static function compareComponents(BookingComponent $a, BookingComponent $b): int
    {
        if ($a->owned && !$b->owned) return -1;
        if ($b->owned && !$a->owned) return 1;
        return static::compareStarts($a, $b);
    }
}
