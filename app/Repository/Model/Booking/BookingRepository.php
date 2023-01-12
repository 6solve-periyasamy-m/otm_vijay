<?php

namespace App\Repository\Model\Booking;

use App\Exceptions\NotOnTourException;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Group;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Order\Order;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\ModelRepository;
use Carbon\Carbon;
use DB;
use Log;
use Throwable;

class BookingRepository extends ModelRepository
{
    private Booking $booking;

    public function __construct(?Booking $booking)
    {
        $this->booking = $booking;
    }

    public static function make(Tour $tour): Booking
    {
        do {
            $token = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(64 / strlen($x)))), 1, 64);
            $booking = Booking::where('token', $token)->first();
        } while (isset($booking));
        return Booking::make(['token' => $token, 'tour_id' => $tour->id]);
    }

    public function upgradeActivityForAll(ActivityInventoryTour $from, ActivityInventoryTour $to): bool
    {
        if ($to->available_stock < $this->booking->travellers()->count()) return false;
        if (!$to->is_bookable) return false;
        try {
            DB::beginTransaction();
            foreach ($this->booking->travellers as $traveller) {
                $success = $traveller->repository->upgradeActivity($from, $to, true);
                if (!$success) {
                    DB::rollBack();
                    return false;
                }
            }
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
            return false;
        }
        return true;
    }

    public function addComponentToAll(InventoryTourRepository $repository): void
    {
        foreach ($this->booking->travellers as $traveller) {
            $repository->grantToBookingTraveller($traveller);
        }
    }

    public function removeComponentFromAll(InventoryTourRepository $repository): void
    {
        foreach ($this->booking->travellers as $traveller) {
            $repository->getBookingComponent($traveller)?->delete();
        }
    }

    public function delete(): bool
    {
        return $this->booking->delete();
    }

    public function addIncludedToAll(): void
    {
        $components = $this->booking->tour->repository->getComponents(false, true, false, true, false, ['Included',]);
        foreach ($this->booking->travellers as $traveller) {
            $traveller->repository->addComponents($components);
            foreach ($this->booking->tour->flightInventoryTours()->where('flight_type', '=', 'Mid-Package')->get() as $flight) {
                $traveller->repository->addComponent($flight->repository);
            }
        }
    }

    public function getTotalCost(): float
    {
        $cost = $this->booking->tour->booking_fee ?? 0;
        foreach ($this->booking->travellers as $traveller) {
            $cost += $traveller->total_cost;
        }
        return $cost;
    }

    public function getDueTodayAmount(): float
    {
        return ($this->booking->tour->booking_fee ?? 0) + ($this->booking->tour->deposit * $this->booking->travellers()->count());
    }

    public function getSingleOccupancyCount(): int
    {
        $count = 0;
        foreach ($this->booking->travellers as $traveller) {
            $count += $traveller->has_single_occupancy;
        }
        return $count;
    }

    public function getSingleOccupancyAmount(): float
    {
        $cost = 0;
        foreach ($this->booking->travellers as $traveller) {
            $cost += $traveller->surcharge_amount;
        }
        return $cost;
    }

    /**
     * @throws NotOnTourException
     */
    public function selectFlights(?int $inbound, ?int $outbound)
    {
        $inboundFlight = FlightInventoryTour::find($inbound);
        $outboundFlight = FlightInventoryTour::find($outbound);
        if (isset($inboundFlight) && $inboundFlight->tour_id !== $this->booking->tour_id) {
            throw new NotOnTourException('The inbound flight is not on the booked tour');
        }
        if (isset($outboundFlight) && $outboundFlight->tour_id !== $this->booking->tour_id) {
            throw new NotOnTourException('The outbound flight is not on the booked tour');
        }
        foreach ($this->booking->travellers as $traveller) {
            $traveller->repository->selectFlights($inboundFlight, $outboundFlight);
        }
    }

    public function getAvailableFlights(): array
    {
        $selected = $this?->booking->leadTraveller?->repository->getSelectedFlights() ?? ['outbound' => 0, 'inbound' => 0];
        $flights = ['outbound' => [], 'inbound' => [],];
        foreach ($this->booking->tour->flightInventoryTours as $flight) {
            //if ($flight->available_stock <= 0) continue; // Disabled due to lack of current requirement
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

    public function __toString(): string
    {
        return "{{$this->booking->token}} - {$this->booking->tour->name}";
    }

    public function convertToOrder(?Carbon $orderedOn = null): Order
    {
        $tour = $this->booking->tour;
        $order = Order::create([
            'tour_id' => $this->booking->tour_id,
            'token' => $this->booking->token,
            'deposit' => $tour->deposit,
            'invoice_footer' => $tour->invoice_footer,
            'ordered_on' => $orderedOn ?? now(),
            'booking_fee' => $tour->booking_fee,
        ]);
        foreach ($this->booking->travellers as $traveller) {
            $orderCustomer = $traveller->repository->convertToOrderCustomer($order);

            if ($traveller->id == $this->booking->lead_traveller_id) {
                $order->lead_booker_id = $orderCustomer->id;
                $order->save();
            }
        }
        $order->booking_reference = Order::generateBookingReference($order);
        $order->repository->save();
        foreach ($this->booking->groups as $bookingGroup) {
            $group = Group::create([
                'name' => $bookingGroup->name,
                'room_type_id' => $bookingGroup->travellers()->first()->room_type_id
            ]);
            foreach ($bookingGroup->travellers as $traveller) {
                $group->repository->addCustomerToGroup($traveller->orderCustomer);
            }
            foreach ($bookingGroup->accommodation as $room) {
                $group->repository->addRoomToGroup($room->tourComponent);
            }
        }
        $order->repository->resetInstallments();
        return $order;
    }

    public static function create(Tour $tour, BookingTraveller $leadTraveller): Booking
    {
        do {
            $token = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(64 / strlen($x)))), 1, 64);
            $booking = Booking::where('token', $token)->first();
        } while (isset($booking));
        $booking = Booking::create(['token' => $token, 'tour_id' => $tour->id]);
        $booking->travellers()->save($leadTraveller);
        $booking->lead_traveller_id = $leadTraveller->id;
        $booking->save();
        return $booking;
    }

    public function save(): bool
    {
        return $this->booking->save();
    }

    public function update(array $data): Booking
    {
        $this->booking->update($data);
        $this->save();
        return $this->get();
    }

    public function get(): Booking
    {
        return $this->booking;
    }

    public function isDeleted(): bool
    {
        return !isset($this->booking);
    }
}
