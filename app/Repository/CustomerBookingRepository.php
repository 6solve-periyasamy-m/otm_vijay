<?php

namespace App\Repository;

use App\Events\Order\OrderCreatedEvent;
use App\Models\Booking\AccommodationGroup;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingMerchandise;
use App\Models\Booking\BookingAccommodation;
use App\Models\Booking\BookingActivity;
use App\Models\Booking\BookingFlight;
use App\Models\Booking\BookingTransport;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Order\Order;
use App\Models\Tour\Merchandise;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\OrderCustomer;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;
use App\Models\Accommodation\RoomType;
use App\Models\Tour\Tour;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use Log;
use StringFormatter;
use Throwable;

class CustomerBookingRepository
{
    public static function generateBooking(Tour $tour, Customer $customer, RoomType $roomType, AccommodationGroup $group, ?string $token = null): Booking
    {
        if (isset($token)) {
            $booking = Booking::where('token', $token)->first();
            if (isset($booking) && $booking->customer_id == $customer->id) {
                $booking = self::clearBooking($booking);
                $traveller = $booking->travellers()->save(BookingTraveller::make(['customer_id' => $customer->id,]));
                /** @var BookingTraveller $traveller */
                //self::addIncludedToBookingTraveller($traveller, $roomType, $group);
                return $booking;
            }
        }

        do {
            $token = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(64/strlen($x)) )),1,64);
            $booking = Booking::where('token', $token)->first();
        } while (isset($booking));

        $name = $customer->first_name . ' ' . $customer->last_name . ' [' . $tour->name . ']';
        $booking = Booking::create(['customer_id' => $customer->id, 'tour_id' => $tour->id, 'token' => $token, 'name' => $name,]);
        $traveller = $booking->travellers()->save(BookingTraveller::make(['customer_id' => $customer->id,]));
        //self::addIncludedToBookingTraveller($traveller, $roomType, $group);
        return $booking;
    }

    public static function clearBooking(Booking $booking): Booking
    {
        $booking->travellers()->delete();
        $booking->accommodation()->delete();
        $booking->activities()->delete();
        $booking->flights()->delete();
        $booking->transports()->delete();
        return $booking;
    }

    public static function addCustomerToBooking(Booking $booking, Customer $customer, RoomType $roomType, AccommodationGroup $group): BookingTraveller
    {
        $traveller = $booking->travellers()->save(BookingTraveller::make(['customer_id' => $customer->id,]));
        /** @var BookingTraveller $traveller */
        //self::addIncludedToBookingTraveller($traveller, $roomType, $group);
        return $traveller;
    }

    public static function removeCustomerFromBooking(Booking $booking, Customer $customer): bool
    {
        $traveller = BookingTraveller::where('customer_id', $customer->id)->where('booking_id', $booking->id)->first();
        if (!isset($traveller)) return false;
        $traveller->merchandise()->delete();
        $traveller->accommodation()->delete();
        $traveller->activities()->delete();
        $traveller->flights()->delete();
        $traveller->transport()->delete();
        $traveller->delete();
        return true;
    }

    #[ArrayShape(['outbound' => "array", 'inbound' => "array"])]
    public static function getAvailableFlights(Tour $tour, ?Booking $booking = null): array
    {
        $selected = self::getSelectedFlights($booking);
        $flights = ['outbound' => [], 'inbound' => [],];
        foreach ($tour->flightInventoryTours as $flight) {
            if ($flight->available_stock <= 0) continue;
            if (!$flight->is_bookable) continue;
            if ($flight->flight_type == 'Outbound') {
                $flights['outbound'][] =
                        ['id' => $flight->id,
                         'details' => $flight->__toString(),
                         'cost' => $flight->tour_component_type == 'Included' ? 0 : $flight->tour_sales_price,
                         'selected' => $selected['outbound'] == $flight->id,];
            } else if ($flight->flight_type == 'Inbound') {
                $flights['inbound'][] =
                        ['id' => $flight->id,
                         'details' => $flight->__toString(),
                         'cost' => $flight->tour_component_type == 'Included' ? 0 : $flight->tour_sales_price,
                         'selected' => $selected['inbound'] == $flight->id,];
            }
        }
        return $flights;
    }

    #[ArrayShape(['outbound' => "int", 'inbound' => "int"])]
    public static function getSelectedFlights(?Booking $booking = null): array
    {
        $selected = ['outbound' => 0, 'inbound' => 0,];
        if (!isset($booking)) return $selected;
        // TODO: Rework when Per-Customer components
        foreach ($booking->flights as $flight) {
            if ($flight->tourComponent->flight_type == 'Outbound') {
                $selected['outbound'] = $flight->flight_inventory_tour_id;
            } else if ($flight->tourComponent->flight_type == 'Inbound') {
                $selected['inbound'] = $flight->flight_inventory_tour_id;
            }
        }
        return $selected;
    }

    public static function selectFlights(Booking $booking, ?FlightInventoryTour $outbound, ?FlightInventoryTour $inbound): bool
    {
        /** @var Tour $tour */
        $tour = $booking->tour;
        if ((isset($outbound) && $outbound?->tour_id !== $tour?->id) || (isset($inbound) && $inbound?->tour_id !== $tour?->id)) return false;
        $booking->flights()->delete();
        foreach ($booking->travellers as $traveller) {
            if (isset($outbound)) {
                if (!$outbound->is_bookable) return false;
                $bookingComponent = BookingFlight::make([
                    'customer_id' => $traveller->customer_id,
                    'flight_inventory_tour_id' => $outbound->id,
                    'flight_type' => $outbound->flight_type,
                ]);
                $booking->flights()->save($bookingComponent);
            }
            if (isset($inbound)) {
                if (!$inbound->is_bookable) return false;
                $bookingComponent = BookingFlight::make([
                    'customer_id' => $traveller->customer_id,
                    'flight_inventory_tour_id' => $inbound->id,
                    'flight_type' => $inbound->flight_type,
                ]);
                $booking->flights()->save($bookingComponent);
            }
        }
        return true;
    }

    public static function addIncludedToBookingTraveller(BookingTraveller $traveller, RoomType $roomType, AccommodationGroup $group)
    {
        $booking = $traveller->booking;
        $tour = $booking->tour;
        // Accommodation
        foreach (AccommodationComponentRepository::getTemplateTourInventory($tour) as $template) {
            $found = AccommodationComponentRepository::getInventoryWithRoomType($template, $roomType);
            if (!isset($found)) {
                $types = AccommodationComponentRepository::hydrateRoomTypes(AccommodationComponentRepository::getRoomTypesForInventory($template));
                foreach ($types as $type) {
                    if ($type->maximum_occupancy == $roomType->maximum_occupancy) {
                        $found = AccommodationComponentRepository::getInventoryWithRoomType($template, $type);
                        if ($found->tour_component_type == 'Included') break;
                    }
                }
            }
            if ($found->tour_component_type == 'Included') {
                $bookingComponent = BookingAccommodation::make([
                    'customer_id' => $traveller->customer_id,
                    'accommodation_inventory_tour_id' => $found->id,
                    'room_type_id' => $roomType->id,
                    'group_id' => $group->id,
                ]);
                $booking->accommodation()->save($bookingComponent);
            }
        }
        // Activity
        foreach ($tour->activityInventoryTours as $inventoryTour) {
            if (!$inventoryTour->is_bookable) continue;
            if ($inventoryTour->tour_component_type == 'Included') {
                if ($inventoryTour->available_stock < $traveller->booking->travellers()->count()) {
                    foreach ($inventoryTour->upgrades as $upgrade) {
                        if ($upgrade->upgrade->available_stock < $traveller->booking->travellers()->count()) continue;
                        $bookingComponent = BookingActivity::make([
                            'customer_id' => $traveller->customer_id,
                            'activity_inventory_tour_id' => $upgrade->upgrade->id,
                        ]);
                        break;
                    }
                } else {
                    $bookingComponent = BookingActivity::make([
                        'customer_id' => $traveller->customer_id,
                        'activity_inventory_tour_id' => $inventoryTour->id,
                    ]);
                }
                if (isset($bookingComponent)) {
                    $booking->activities()->save($bookingComponent);
                }
            }
        }
        // Transport
        foreach ($tour->transportInventoryTours as $inventoryTour) {
            if (!$inventoryTour->is_bookable) continue;
            if ($inventoryTour->tour_component_type == 'Included') {
                $bookingComponent = BookingTransport::make([
                    'customer_id' => $traveller->customer_id,
                    'transport_inventory_tour_id' => $inventoryTour->id,
                ]);
                $booking->transports()->save($bookingComponent);
            }
        }
        foreach ($tour->merchandise as $inventoryTour) {
            if (!$inventoryTour->is_bookable) continue;
            if ($inventoryTour->tour_component_type == 'Included') {
                $bookingComponent = BookingMerchandise::make([
                    'customer_id' => $traveller->customer_id,
                    'merchandise_id' => $inventoryTour->id,
                ]);
                $booking->merchandise()->save($bookingComponent);
            }
        }
    }

    /**
     * @throws Throwable
     */
    public static function upgradeBookingActivity(Booking $booking, ActivityInventoryTour $from, ActivityInventoryTour $to): bool
    {
        if (($booking->tour_id !== $from->tour_id) || ($booking->tour_id !== $to->tour_id)) return false;
        if (!$to->is_bookable) return false;
        if ($to->available_stock < $booking->travellers()->count()) return false;
        try {
            DB::beginTransaction();
            DB::table('booking_activities')
                ->where('booking_id', '=', $booking->id)
                ->where('activity_inventory_tour_id', '=', $from->id)
                ->update(['activity_inventory_tour_id' => $to->id,]);
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
            return false;
        }
        return true;
    }

    public static function addBookingActivityAddon(Booking $booking, ActivityInventoryTour $addon): bool
    {
        if ($booking->tour_id !== $addon->tour_id) return false;
        if (!$addon->is_bookable) return false;
        if ($addon->tour_component_type !== 'Add-on') return false;
        if ($addon->available_stock < $booking->travellers()->count()) return false;
        foreach ($booking->travellers as $traveller) {
            $booking->activities()->save(BookingActivity::make(['customer_id' => $traveller->customer->id,'activity_inventory_tour_id' => $addon->id,]));
        }
        return true;
    }

    public static function addBookingMerchandiseAddon(Booking $booking, Merchandise $addon): bool
    {
        if ($booking->tour_id !== $addon->tour_id) return false;
        if (!$addon->is_bookable) return false;
        if ($addon->tour_component_type !== 'Add-on') return false;
        if ($addon->available_stock < $booking->travellers()->count()) return false;
        foreach ($booking->travellers as $traveller) {
            $booking->merchandise()->save(BookingMerchandise::make(['customer_id' => $traveller->customer->id,'merchandise_id' => $addon->id,]));
        }
        return true;
    }

    public static function removeBookingActivityAddon(Booking $booking, ActivityInventoryTour $addon): bool
    {
        if ($booking->tour_id !== $addon->tour_id) return false;
        if ($addon->tour_component_type !== 'Add-on') return false;
        foreach ($booking->activities as $bookingComponent) {
            if ($bookingComponent->tourComponent->id == $addon->id)
                $bookingComponent->delete();
        }
        return true;
    }

    public static function removeBookingMerchandiseAddon(Booking $booking, Merchandise $addon): bool
    {
        if ($booking->tour_id !== $addon->tour_id) return false;
        if ($addon->tour_component_type !== 'Add-on') return false;
        foreach ($booking->merchandise as $bookingComponent) {
            if ($bookingComponent->tourComponent->id == $addon->id)
                $bookingComponent->delete();
        }
        return true;
    }

    public static function getBookingAdditionals(BookingTraveller $bookingTraveller): array
    {
        if (!isset($bookingTraveller)) return [];
        $owned = self::getBookedComponentsAndUpgradedIncluded($bookingTraveller);
        $data = [];
        foreach ($bookingTraveller->booking->tour->merchandise as $tourComponent) {
            $owns = in_array($tourComponent->id, $owned['extras']);
            if ($tourComponent->available_stock <= 0 && !$owned) continue;
            $data[] = ['id' => $tourComponent->id, 'name' => $tourComponent->name, 'component' => 'extra', 'type' => $tourComponent->tour_component_type,
                'cost' => $tourComponent->tour_sales_price, 'date' => now()->unix(), 'owned' => $owns,];
        }
        foreach ($bookingTraveller->booking->tour->activityInventoryTours as $tourComponent) {
            if ($tourComponent->tour_component_type !== 'Upgrade') {
                $inventory = $tourComponent->inventory;
                if (in_array($tourComponent->id, $owned['activities'])) continue; // Owned components will be shown elsewhere
                if ($tourComponent->available_stock <= 0) continue;
                $data[] = ['id' => $tourComponent->id, 'name' => $inventory->__toString(), 'component' => 'activity', 'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_sales_price, 'date' => $inventory->starts_at->unix(),'owned' => false,];
            }
        }
        return $data;
    }

    /**
     * Mostly the same in implementation to Order equivalent. Generates more data than is needed, as it is not worth
     * stripping it out when it needs adding again later
     * @param BookingTraveller $traveller
     * @return array
     */
    public static function getBookedComponentsAndUpgradedIncluded(BookingTraveller $traveller): array
    {
        $data = [];
        $subData = [];
        foreach ($traveller->accommodation as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['accommodation'] = array_unique($subData);
        $subData = [];
        foreach ($traveller->activities as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['activities'] = array_unique($subData);
        $subData = [];
        foreach ($traveller->flights as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['flights'] = array_unique($subData);
        $subData = [];
        foreach ($traveller->transport as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['transport'] = array_unique($subData);
        $subData = [];
        foreach ($traveller->merchandise as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
        }
        $data['extras'] = array_unique($subData);
        return $data;
    }

    public static function generateSummary(Booking $booking): array
    {
        $tour = $booking->tour;
        $summary = ['customers' => [], 'travellers' => [], 'billing' => ['additionals' => 0, 'single_occupants' => 0, 'surcharge' => $tour->single_occupancy_surcharge, 'cost' => $tour->base_price_per_person, 'deposit' => $tour->deposit],];
        $customerCount = 0;
        foreach ($booking->travellers as $traveller) {
            $summary['travellers'][] = $traveller;
            $customerCount++;
            $customer = $traveller->customer;
            $summary['customers'][$customer->id] = ['customer' => $customer, 'components' => ['accommodation' => [], 'activities' => [], 'flights' => [], 'transport' => [],]];
            $summary['billing']['single_occupants'] = $summary['billing']['single_occupants'] + $traveller->is_single_occupant;
            // Accommodation
            $travellerAccommodation = $traveller->accommodation->getIterator();
            $travellerAccommodation->uasort([BookingAccommodation::class, 'compare']);
            //dd($travellerAccommodation, $traveller->accommodation);
            foreach ($travellerAccommodation as $bookingComponent) {
                $tourComponent = $bookingComponent->tourComponent;
                $inventory = $tourComponent->inventory;
                $summary['customers'][$bookingComponent->customer_id]['components']['accommodation'][] = [
                    'time' => StringFormatter::formatDateTime($inventory->check_in) . ' to ' . StringFormatter::formatDateTime($inventory->check_out),
                    'description' => $inventory->component->name . ' (' . $inventory->component->address?->region . ', ' . $inventory->component->address?->country?->name . ') (' . $inventory->boardType . ')',
                    'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price,
                    'component' => $bookingComponent,
                ];
                $summary['billing']['additionals'] += $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price;
            }
            // Activity
            $travellerActivities = $traveller->activities->getIterator();
            $travellerActivities->uasort([BookingActivity::class, 'compare']);
            foreach ($travellerActivities as $bookingComponent) {
                $tourComponent = $bookingComponent->tourComponent;
                $inventory = $tourComponent->inventory;
                $summary['customers'][$bookingComponent->customer_id]['components']['activities'][] = [
                    'time' => StringFormatter::formatDateTime($inventory->starts_at) . ' to ' . StringFormatter::formatDateTime($inventory->ends_at),
                    'description' => $inventory->component->name . ' (' . $inventory->component->address . ')' . ' (' . $inventory->ticketType . ')',
                    'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price,
                    'component' => $bookingComponent,
                ];
                $summary['billing']['additionals'] += $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price;
            }
            // Flights
            $travellerFlights = $traveller->flights->getIterator();
            $travellerFlights->uasort([BookingFlight::class, 'compare']);
            foreach ($travellerFlights as $bookingComponent) {
                $tourComponent = $bookingComponent->tourComponent;
                $inventory = $tourComponent->inventory;
                $summary['customers'][$bookingComponent->customer_id]['components']['flights'][] = [
                    'time' => StringFormatter::formatDateTime($inventory->check_in) . ' to ' . StringFormatter::formatDateTime($inventory->arrives_at),
                    'description' => $inventory->component->departureAirport . ' to ' . $inventory->component->arrivalAirport . ' (' . $inventory->travelClass . ')',
                    'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price,
                    'component' => $bookingComponent,
                ];
                $summary['billing']['additionals'] += $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price;
            }
            // Transport
            $travellerTransport = $traveller->transport->getIterator();
            $travellerTransport->uasort([BookingTransport::class, 'compare']);
            foreach ($travellerTransport as $bookingComponent) {
                $tourComponent = $bookingComponent->tourComponent;
                $inventory = $tourComponent->inventory;
                $summary['customers'][$bookingComponent->customer_id]['components']['transport'][] = [
                    'time' => StringFormatter::formatDateTime($inventory->departs_at) . ' to ' . StringFormatter::formatDateTime($inventory->arrives_at),
                    'description' => $inventory->component->name . ' (' . $inventory->component->transportType . ') (' . $inventory->travelClass . ')',
                    'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price,
                    'component' => $bookingComponent,
                ];
                $summary['billing']['additionals'] += $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price;
            }
            foreach ($traveller->merchandise as $bookingComponent) {
                $tourComponent = $bookingComponent->tourComponent;
                $summary['billing']['additionals'] += $tourComponent->tour_component_type == 'Included' ? 0 : $tourComponent->tour_sales_price;
            }
            // Addons
            $summary['customers'][$bookingComponent->customer_id]['addons'] = self::getBookingAdditionals($traveller);
        }
        $summary['billing']['customers'] = $customerCount;
        $summary['billing']['total'] = ($tour->base_price_per_person * $customerCount) + ($summary['billing']['single_occupants'] * $summary['billing']['surcharge']) + $summary['billing']['additionals'];
        $summary['billing']['today'] = $tour->deposit * $customerCount;
        //dd($summary);
        return $summary;
    }

    public static function getLeadTraveller(?Booking $booking): ?BookingTraveller
    {
        if (!isset($booking)) return null;
        return BookingTraveller::where('booking_id', $booking->id)->where('customer_id', $booking->customer_id)->first();
    }

    public static function getAdditionalTravellers(?Booking $booking): array
    {
        if (!isset($booking)) return [];
        $customers = [];
        foreach ($booking->travellers as $traveller) {
            $customers[$traveller->customer_id] = $traveller;
        }
        $customers[$booking->customer_id] = null;
        return $customers;

    }

    public static function convertBookingToOrder(Booking $booking): Order
    {
        $tour = $booking->tour;
        $order = Order::create([
            'tour_id' => $booking->tour_id,
            'ordered_on' => now(),
            'deposit' => $tour->deposit,
            'invoice_footer' => $tour->invoice_footer
        ]);
        $leadBooker = OrderCustomer::make([
            'customer_id' => $booking->customer_id,
            'tour_cost' => $tour->base_price_per_person,
            'single_occupancy_surcharge' => $tour->single_occupancy_surcharge,
        ]);

        StaticOrderRepository::cloneInstallments($order);

        $customers = [];
        $order->orderCustomers()->save($leadBooker);

        $customers[$booking->customer_id] = $leadBooker;
        $order->lead_booker_id = $leadBooker->id;
        $order->booking_reference = Order::generateBookingReference($order);
        $order->save();
        //event(new OrderCreatedEvent($order));
        $leadTraveller = self::getLeadTraveller($booking);
        self::buildComponents($order, $leadTraveller, $leadBooker);
        $orderCustomers = [$leadBooker->customer_id => $leadBooker,];
        foreach ($booking->travellers as $traveller) {
            if ($traveller->id == $leadTraveller->id) continue;
            $orderCustomer = self::buildComponents($order, $traveller);
            $orderCustomers[$orderCustomer->customer_id] = $orderCustomer;
        }
        $groups = [];
        foreach ($booking->accommodation as $accommodation) {
            $group = key_exists($accommodation->group_id, $groups) ? $groups[$accommodation->group_id]
                : Group::create(['name' => $accommodation->group->name, 'room_type_id' => $accommodation->room_type_id,]);
            $groups[$accommodation->group_id] = $group;
            $oCustomer = $orderCustomers[$accommodation->customer_id];
            $groupRepo = new GroupRepository($group);
            $groupRepo->addCustomerToGroup($oCustomer);
            $groupRepo->addRoomToGroup($accommodation->tourComponent);
        }
        return $order;
    }

    private static function buildComponents(Order $order, BookingTraveller $traveller, ?OrderCustomer $orderCustomer = null): OrderCustomer
    {
        if (!isset($orderCustomer)) {
            $orderCustomer = OrderCustomer::make([
                'customer_id' => $traveller->customer_id,
                'tour_cost' => $order->tour->base_price_per_person,
                'single_occupancy_surcharge' => $order->tour->single_occupancy_surcharge,
            ]);
            $order->orderCustomers()->save($orderCustomer);
        }
        foreach ($traveller->activities as $bookingComponent) {
            $orderCustomer->orderActivities()->save(OrderActivity::make([
                'activity_inventory_tour_id' => $bookingComponent->tourComponent->id,
                'cost' => $bookingComponent->tourComponent->tour_sales_price,
            ]));
        }
        foreach ($traveller->flights as $bookingComponent) {
            $orderCustomer->orderFlights()->save(OrderFlight::make([
                'flight_inventory_tour_id' => $bookingComponent->tourComponent->id,
                'cost' => $bookingComponent->tourComponent->tour_sales_price,
            ]));
        }
        foreach ($traveller->transport as $bookingComponent) {
            $orderCustomer->orderTransports()->save(OrderTransport::make([
                'transport_inventory_tour_id' => $bookingComponent->tourComponent->id,
                'cost' => $bookingComponent->tourComponent->tour_sales_price,
            ]));
        }
        foreach ($traveller->merchandise as $bookingComponent) {
            $orderCustomer->orderMerchandise()->save(OrderMerchandise::make([
                'merchandise_id' => $bookingComponent->tourComponent->id,
                'cost' => $bookingComponent->tourComponent->tour_sales_price,
            ]));
        }
        return $orderCustomer;
    }
}
