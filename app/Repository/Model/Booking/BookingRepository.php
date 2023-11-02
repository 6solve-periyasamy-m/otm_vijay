<?php

namespace App\Repository\Model\Booking;

use App\Exceptions\NotOnTourException;
use App\Exceptions\RemoteGatewayError;
use App\Exceptions\RoomingFailedException;
use App\Exceptions\UnauthorizedGatewayException;
use App\Http\Gateways\Storage\LineItem;
use App\Models\Accommodation\RoomType;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingGroup;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Group;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Order\Order;
use App\Models\Order\Payment\PaymentIntention;
use App\Models\System\FellohLink;
use App\Models\Tour\Tour;
use App\Models\Voucher\Executors\FlatCostReductionExecutor;
use App\Models\Voucher\Executors\PercentageCostReductionExecutor;
use App\Models\Voucher\VoucherCode;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Interfaces\GeneratesFellohData;
use App\Repository\RoomingRepository;
use App\Repository\Storage\Rooming\RemoteBookingGroup;
use Carbon\Carbon;
use DB;
use Gateway;
use Log;
use Throwable;

class BookingRepository extends ModelRepository implements GeneratesFellohData
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
        if (!$to->repository->hasEnoughStock($this->booking->travellers()->count())) return false;
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

    public function applyVoucher(VoucherCode $voucher): bool
    {
        return $this->booking->leadTraveller->repository->applyVoucher($voucher);
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
            foreach ($this->booking->tour->flightInventoryTours()->where('flight_type', '=', 'Mid-Package')->where('tour_component_type', '=', 'Included')->get() as $flight) {
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
        /** @var VoucherCode $voucher */
        foreach ($this->booking->vouchers()->get() as $voucher) {
            foreach ($voucher->results as $result) {
                $executor = $result->executor();
                if ($executor instanceof FlatCostReductionExecutor) {
                    $cost += $executor->getAmount();
                }
                if ($executor instanceof PercentageCostReductionExecutor) {
                    $cost -= $executor->getAmount($this->booking->tour->base_price_per_person);
                }
            }
        }
        return $cost;
    }

    public function getDueTodayAmount(): float
    {
        $travellers = $this->booking->travellers()->count();
        $upfront = ($this->booking->tour->booking_fee ?? 0) + (($this->booking->tour->deposit ?? 0) * $travellers);
        if (flag('installments.force', false)) {
            if ($this->booking->tour->final_payment->isBefore(now())) {
                return $this->getTotalCost();
            }
            foreach ($this->booking->tour->paymentInstallments as $installment) {
                if ($installment->due_on->isBefore(now())) {
                    $upfront += $installment->amount * $travellers;
                }
            }
        }
        return $upfront;
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
        $outboundIncluded = false;
        $outboundSelected = false;
        $inboundIncluded = false;
        $inboundSelected = false;
        foreach ($this->booking->tour->flightInventoryTours as $flight) {
            if (!$flight->repository->hasEnoughStock($this->booking->travellers()->count())) continue;
            if (!$flight->is_bookable) continue;
            if ($flight->flight_type == 'Outbound') {
                if ($flight->tour_component_type == 'Included') $outboundIncluded = true;
                if ($selected['outbound'] == $flight->id) $outboundSelected = true;
                $flights['outbound'][] =
                    ['id' => $flight->id,
                        'details' => $flight->__toString(),
                        'cost' => $flight->tour_component_type == 'Included' ? 0 : $flight->tour_sales_price,
                        'selected' => $selected['outbound'] == $flight->id,];
            } else if ($flight->flight_type == 'Inbound') {
                if ($flight->tour_component_type == 'Included') $inboundIncluded = true;
                if ($selected['inbound'] == $flight->id) $inboundSelected = true;
                $flights['inbound'][] =
                    ['id' => $flight->id,
                        'details' => $flight->__toString(),
                        'cost' => $flight->tour_component_type == 'Included' ? 0 : $flight->tour_sales_price,
                        'selected' => $selected['inbound'] == $flight->id,];
            }
        }
        usort($flights['outbound'], function ($flight1, $flight2) { return $flight1['cost'] - $flight2['cost']; });
        usort($flights['inbound'], function ($flight1, $flight2) { return $flight1['cost'] - $flight2['cost']; });
        if (!$outboundIncluded) {
            array_unshift($flights['outbound'], ['id' => 0, 'details' => 'No Flight', 'cost' => 0, 'selected' => $outboundSelected]);
        }
        if (!$inboundIncluded) {
            array_unshift($flights['inbound'], ['id' => 0, 'details' => 'No Flight', 'cost' => 0, 'selected' => $inboundSelected]);
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
        $this->booking->order_id = $order->id;
        $this->booking->save();
        foreach ($this->booking->groups as $bookingGroup) {
            $group = Group::create();
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

    /**
     * @throws RemoteGatewayError
     * @throws UnauthorizedGatewayException
     */
    public function getGatewayUrl(float $amount)
    {
        $gateway = Gateway::getDefaultGateway();
        $item = new LineItem("Deposit for Booking from {$this->booking->leadTraveller->full_name}", $amount);
        $intention = PaymentIntention::build($this->booking->leadTraveller->customer, $this->booking->token, 'Deposit');

        $redirect = setting('booking.success.redirect', route('payment.gateway.stripe.success'));
        return $gateway->checkout([$item,], $intention, $this->booking->leadTraveller, $redirect);
    }

    public function getRoomingData(): array
    {
        $rooms = [];
        foreach ($this->booking->tour->accommodationInventoryTours()->with('inventory', 'inventory.component')->get() as $inventoryTour) {
            $rooms[$inventoryTour->id] = [
                'name' => $inventoryTour->repository->formatAdminOccupancy(),
                'size' => $inventoryTour->inventory->roomType->maximum_occupancy,
                'price' => $inventoryTour->tour_component_type === 'Included' ? 0 : $inventoryTour->tour_sales_price,
                'start' => $inventoryTour->inventory->check_in->unix(),
                'end' => $inventoryTour->inventory->check_out->unix(),
                'available' => !$inventoryTour->repository->isStockControlActive() || $inventoryTour->repository->hasEnoughStock($this->booking->travellers()->count())
            ];
        }
        $customers = [];
        foreach ($this->booking->travellers()->get() as $traveller) {
            $customers[$traveller->id] = ['name' => $traveller->full_name, 'avatar' => null,];
        }
        $groups = [];
        foreach ($this->booking->groups as $group) {
            $groupCustomers = [];
            foreach ($group->travellers as $traveller) {
                $groupCustomers[] = $traveller->id;
            }
            $groupRooms = [];
            foreach ($group->accommodation as $room) {
                $groupRooms[] = $room->accommodation_inventory_tour_id;
            }
            $groups[$group->id] = ['rooms' => $groupRooms, 'customers' => $groupCustomers,];
        }
        return ['rooms' => $rooms, 'customers' => $customers, 'groups' => $groups,];
    }

    private function wipeGroups()
    {
        foreach ($this->booking->groups as $group) {
            $group->delete();
        }
    }

    /**
     * @param RemoteBookingGroup[] $remoteGroups
     * @return void
     */
    public function importRoomingData(array $remoteGroups): void
    {
        $this->wipeGroups();
        foreach ($remoteGroups as $remoteGroup) {
            $remoteGroup->convertToGroup($this->booking);
        }
    }

    public function getRemainingInstallmentAmount(): float|int
    {
        $base = 0;
        foreach ($this->booking->travellers as $traveller) {
            $base += $this->booking->tour->remaining_installment;
            $base += $traveller->surcharge_amount;
            $base += $traveller->additional_cost;
            foreach ($traveller->vouchers()->get() as $voucher) {
                foreach ($voucher->results as $result) {
                    $executor = $result->executor();
                    if ($executor instanceof FlatCostReductionExecutor) {
                        $base += $executor->getAmount();
                    }
                    if ($executor instanceof PercentageCostReductionExecutor) {
                        $base += $executor->getAmount($traveller->base_cost);
                    }
                }
            }
        }
        return $base;
    }

    public function evaluateSimpleRooming()
    {
        if (!$this->hasRooming()) return;
        $this->wipeGroups();
        $groups = [];
        foreach ($this->booking->travellers as $traveller) {
            if ($traveller->group_id === null || $traveller->room_type_id === null) {
                $group = BookingGroup::create(['booking_id' => $this->booking->id,]);
                $group->repository->addTravellerToGroup($traveller);
                $single = RoomingRepository::getSingleRoomType($this->booking->tour);
                if ($single !== null) {
                    try {
                        $group->repository->addTemplatesOfTypeToGroup($this->booking->tour, $single);
                    } catch (RoomingFailedException) {}
                }
                continue;
            }

            if (!array_key_exists($traveller->group_id, $groups)) {
                $groups[$traveller->group_id] = ['type' => $traveller->roomType, 'group' => BookingGroup::create(['booking_id' => $this->booking->id, 'name' => "Group {$traveller->group_id}"])];
            }
            $data = $groups[$traveller->group_id];
            /** @var BookingGroup $group */
            $group = $data['group'];
            /** @var RoomType $type */
            $type = $data['type'];

            if ($group->travellers()->count() >= $type->maximum_occupancy || $type->id !== $traveller->room_type_id) {
                $found = false;
                $newGroup = null;
                do {
                    $newGroup = ($newGroup ?? $traveller->group_id) + 1;
                    if (!array_key_exists($newGroup, $groups)) {
                        $groups[$newGroup] = ['type' => $traveller->roomType, 'group' => BookingGroup::create(['booking_id' => $this->booking->id, 'name' => "Group $newGroup"])];
                        $traveller->group_id = $newGroup;
                        $traveller->save();
                        $found = true;
                    } else {
                        if ($groups[$newGroup]['type']?->id === $traveller->room_type_id
                            && $groups[$newGroup]['group']->travellers()->count() < $groups[$newGroup]['type']->maximum_occupancy) {
                            $traveller->group_id = $newGroup;
                            $traveller->save();
                            $found = true;
                        } else {
                            \Log::info("{$groups[$newGroup]['type']?->id}, {$traveller->room_type_id}");
                        }
                    }
                } while (!$found);
            }

            $data = $groups[$traveller->group_id];
            /** @var BookingGroup $group */
            $group = $data['group'];

            $group->repository->addTravellerToGroup($traveller);
        }
        foreach ($groups as $data) {
            /** @var BookingGroup $group */
            $group = $data['group'];
            /** @var RoomType $type */
            $type = $data['type'];
            try {
                $group->repository->addTemplatesOfTypeToGroup($this->booking->tour, $type);
            } catch (RoomingFailedException) {}
        }
    }

    public function getBreakdown(): array
    {
        $data = [];
        foreach ($this->booking->travellers as $traveller) {
            $data[$traveller->id] = [
                'name' => $traveller->full_name,
                'cost' => $traveller->repository->getBaseCost(),
                'extras' => $traveller->repository->getExtrasBreakdown(),
            ];
        }
        return $data;
    }

    public function hasRooming(): bool
    {
        return $this->booking->tour->templates->count() > 0;
    }

    public function validateVouchers(): void
    {
        foreach ($this->booking->travellers()->with('vouchers')->get() as $traveller) {
            $traveller->repository->validateVouchers();
        }
    }

    public function getFellohData(): array
    {
        return [
            'customer_name' => $this->booking->leadTraveller->full_name,
            'email' => $this->booking->leadTraveller->email_address,
            'booking_reference' => $this->getReference(),
            'departure_date' => $this->booking->tour->date_from->format('Y-m-d'),
            'return_date' => $this->booking->tour->date_to->format('Y-m-d'),
            'gross_amount' => $this->getTotalCost(),
        ];
    }

    public function getReference(): string
    {
        return $this->booking->token;
    }

    public function getFellohId(): string|null
    {
        return $this->booking->felloh?->felloh_id;
    }

    public function setFellohId(string $id): void
    {
        $current = $this->getFellohId();
        if ($current === $id) { return; }
        if ($current !== null) {
            $this->booking->felloh->felloh_id = $id;
            $this->booking->felloh->save();
        } else {
            $this->booking->felloh()->save(new FellohLink(['felloh_id' => $id]));
        }
    }
}
