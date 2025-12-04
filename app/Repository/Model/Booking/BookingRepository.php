<?php

namespace App\Repository\Model\Booking;

use App\Exceptions\NotOnTourException;
use App\Exceptions\RemoteGatewayError;
use App\Exceptions\RoomingFailedException;
use App\Exceptions\UnauthorizedGatewayException;
use App\Http\Gateways\AirwallexGateway;
use App\Http\Gateways\Storage\LineItem;
use App\Http\Gateways\StripeGateway;
use App\Models\Accommodation\RoomType;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingGroup;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Group;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Helper\Enum\ActivityCategory;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Helper\Enum\BookingTravellerRole;
use App\Models\Location\Address;
use App\Models\Location\Currency;
use App\Models\Order\Order;
use App\Models\Order\Payment\PaymentIntention;
use App\Models\Quote\Quote;
use App\Models\System\FellohLink;
use App\Models\System\TaxBracket;
use App\Models\Tour\Tour;
use App\Models\Voucher\Executors\FlatCostReductionExecutor;
use App\Models\Voucher\Executors\PercentageCostReductionExecutor;
use App\Models\Voucher\VoucherCode;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Interfaces\GeneratesFellohData;
use App\Repository\RoomingRepository;
use App\Repository\Storage\Rooming\RemoteBookingGroup;
use App\Repository\Storage\Tour\GroupedHotelRooming;
use Carbon\Carbon;
use DB;
use Gateway;
use Log;
use Settings;
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

    public static function setupBooking(Tour $tour): Booking
    {
        $booking = self::make($tour);
        $booking->save();
        $lead = $booking->repository->makeTraveller([]);
        $booking->travellers()->save($lead);
        $booking->lead_traveller_id = $lead->id;
        $booking->save();
        $lead->repository->addAllIncluded();
        return $booking;
    }

    public static function pruneConverted(): void
    {
        foreach (Booking::converted()->get() as $booking) {
            $booking->repository->forceDelete();
        }
    }

    public static function pruneOutdated(Carbon|null $before = null): void
    {
        foreach (Booking::before($before)->get() as $booking) {
            $booking->repository->forceDelete();
        }
    }

    public function makeTraveller(array $details): BookingTraveller
    {
        $homeAddress = Address::create(['name' => 'Booking Traveller - Home Address', 'parent' => AddressParent::CUSTOMER,]);
        $billingAddress = Address::create(['name' => 'Booking Traveller - Billing Address', 'parent' => AddressParent::CUSTOMER,]);
        return $this->booking->travellers()->make([
            'home_address_id' => $homeAddress->id,
            'billing_address_id' => $billingAddress->id,
            ...$details,
        ]);
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
        $components = $this->booking->tour?->repository->getComponents(false, true, false, true, false, ['Included',]);
        foreach ($this->booking->travellers as $traveller) {
            $traveller->repository->addComponents($components);
            foreach ($this->booking->tour?->flightInventoryTours()->where('flight_type', '=', 'Mid-Package')->where('tour_component_type', '=', 'Included')->get() as $flight) {
                $traveller->repository->addComponent($flight->repository);
            }
        }
    }

    public function getTotalCost(): float
    {
        $cost = $this->booking->tour?->booking_fee ?? 0;
        foreach ($this->booking->travellers as $traveller) {
            $cost += $traveller->total_cost;
        }
        foreach ($this->booking->groups as $group) {
            $cost += $group->repository->getTotalCost();
        }
        /** @var VoucherCode $voucher */
        foreach ($this->booking->vouchers()->get() as $voucher) {
            foreach ($voucher->results as $result) {
                $executor = $result->executor();
                if ($executor instanceof FlatCostReductionExecutor) {
                    $cost += $executor->getAmount();
                }
                if ($executor instanceof PercentageCostReductionExecutor) {
                    $cost -= $executor->getAmount($this->booking->tour?->base_price_per_person);
                }
            }
        }
        return $cost;
    }

    public function getDueTodayAmount(): float
    {
        $travellers = $this->booking->travellers()->count();
        $upfront = ($this->booking->tour?->booking_fee ?? 0.0);
        if (flag('booking.deposit.full')) {
            $upfront += ((($this->booking->tour?->deposit_percentage ?? 0.0)/100) * ($this->getTotalCost()));
        } else {
            $upfront += (($this->booking->tour?->deposit_amount ?? 0.0) * $travellers);
        }
        if (flag('installments.force', false)) {
            if ($this->booking->tour?->final_payment->isBefore(now())) {
                return $this->getTotalCost();
            }
            foreach ($this->booking->tour?->paymentInstallments as $installment) {
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
        foreach ($this->booking->tour?->flightInventoryTours as $flight) {
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
        return "{{$this->booking->token}} - {$this->booking->tour?->name}";
    }

    public function convertToQuote(): ?Quote
    {
        $tour = $this->booking->tour;
        $lead = $this->booking->leadTraveller;

        if (!$lead || !$lead->email_address) {
            \Log::warning("Cannot create quote: Missing lead email for booking ID {$this->booking->id}");
            return null;
        }

        $customer = $lead->customer ?? (new BookingTravellerRepository($lead))->convertToCustomer();

        $lead->customer_id = $customer->id;
        $lead->save();
        $travellers = $this->booking->travellers()->where('role', '!=', BookingTravellerRole::NORMAL)->count();

        $quote = Quote::create([
            'consultant_id' => get_current_admin()?->id,
            'event_id' => $tour->event_id,
            'deposit' => $tour->deposit,
            'is_deposit_percentage' => $tour->is_deposit_percentage,
            'tax_bracket_id' => $tour->tax_bracket_id ?? $tour->event?->tax_bracket_id,
            'final_payment' => $tour->final_payment,
            'date_from' => $tour->date_from,
            'date_to' => $tour->date_to,
            'terms' => $tour->terms,
            'invoice_footer' => $tour->invoice_footer ?? '',
            'name' => $tour->name,
            'description' => $tour->description,
            'paying' => $travellers,
            'tour_id' => $tour->id,
        ]);

        $leadTraveller = $quote->repository->createProspect($customer, []);
        $quote->lead_traveller_id = $leadTraveller?->id;
        $quote->reference = $quote->repository->generateReference();
        $quote->save();

        foreach ($tour->repository->getComponents(true, true, true, true, true, ['Included']) as $component) {
            $component->addToQuote($quote);
        }

        foreach ($tour->costs as $cost) {
            $quote->costs()->save($cost->replicate());
        }
        $quote->repository->cloneInstallments($tour);
        $quote->repository->addPricePoint(1, $tour->base_price_per_person);
        return $quote;
    }

    public function updateCurrency(string $currency): void
    {
        $this->booking->currency_id = Currency::where('code', '=', $currency)->first()?->id;
        $this->booking->save();
    }

    public function convertToOrder(?Carbon $orderedOn = null): Order
    {
        $tour = $this->booking->tour;
        $travellers = $this->booking->travellers()->where('role', '!=', BookingTravellerRole::NOT_TRAVELLING)->count();
        $total = $this->getTotalCost() * $this->getFXRate();
        if (flag('booking.round_to_five')) {
            $total = round_to_five($total);
        }
        if (flag('booking.deposit.full')) {
            // Since deposit on the order is per-person, if the full cost should be taken into account
            // Then get the total deposit, then divide by paying travellers
            $deposit = ((($this->booking->tour?->deposit_percentage ?? 0.0)/100) * ($total)) / $travellers;
        } else {
            $deposit = (($this->booking->tour?->deposit_amount ?? 0.0) * $this->getFXRate());
        }
        $order = Order::make([
            'tour_id' => $this->booking->tour_id,
            'token' => $this->booking->token,
            'deposit' => $deposit,
            'invoice_footer' => $tour->invoice_footer,
            'ordered_on' => $orderedOn ?? now(),
            'booking_fee' => $tour->booking_fee,
            'internal_notes' => 'Booking Token: ' . $this->booking->token,
            'external_notes' => $this->booking->notes,
            'currency_id' => $this->booking->currency_id,
        ]);
        $order->saveQuietly();
        foreach ($this->booking->travellers as $traveller) {
            $orderCustomer = $traveller->repository->convertToOrderCustomer($order);

            if ($traveller->id == $this->booking->lead_traveller_id) {
                $order->lead_booker_id = $orderCustomer->id;
                $order->save();
            }
        }
        $order->booking_reference = Order::generateBookingReference($order);
        $order->saveQuietly();
        $this->booking->order_id = $order->id;
        $this->booking->save();
        foreach ($this->booking->groups as $bookingGroup) {
            $group = Group::create();
            foreach ($bookingGroup->travellers as $traveller) {
                $group->repository->addCustomerToGroup($traveller->orderCustomer);
            }
            foreach ($bookingGroup->accommodation as $room) {
                $group->repository->addRoomToGroup($room->tourComponent, true);
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
        foreach ($this->booking->tour?->accommodationInventoryTours()->with('inventory', 'inventory.component')->get() as $inventoryTour) {
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
        return ['rooms' => $rooms, 'customers' => $customers, 'groups' => $groups, 'start' => $this->booking->tour->date_from->unix(), 'end' => $this->booking->tour->date_to->unix()];
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
            $base += $this->booking->tour?->remaining_installment;
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
                            Log::info("{$groups[$newGroup]['type']?->id}, {$traveller->room_type_id}");
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
        return $this->booking->tour?->templates->count() > 0;
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
            'departure_date' => $this->booking->tour?->date_from->format('Y-m-d'),
            'return_date' => $this->booking->tour?->date_to->format('Y-m-d'),
            'gross_amount' => (int)($this->getTotalCost()*100),
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

    public function forceDelete(): void
    {
        foreach ($this->booking->groups as $group) {
            $group->accommodation()->delete();
            DB::table('booking_traveller_groups')->where('booking_group_id', '=', $group->id)->delete();
            $group->delete();
        }
        foreach ($this->booking->travellers as $traveller) {
            $traveller->activities()->delete();
            $traveller->flights()->delete();
            $traveller->transport()->delete();
            $traveller->merchandise()->delete();
            $traveller->vouchers()->delete();
            $traveller->delete();
        }
        $this->booking->delete();
    }

    public static function find($id): Booking|null
    {
        return Booking::find($id);
    }

    public function getUpgradeCosts(): float
    {
        $cost = 0;
        foreach ($this->booking->travellers as $traveller) {
            foreach ($traveller->repository->getComponents(false, ['Upgrade', 'Add-on']) as $component) {
                $cost += $component->getCost();
            }
        }
        foreach ($this->booking->groups as $group) {
            foreach ($group->accommodation as $room) {
                if ($room->tourComponent->tour_component_type !== 'Included') {
                    $cost += $room->tourComponent->tour_sales_price;
                }
            }
        }
        return $cost;
    }

    public function convertBookingCurrency(float $cost, string $toCurrency): ?float
    {
        return fx_convert($cost, setting('system.currency'), $toCurrency);
    }

    private function getTaxBracket(): TaxBracket|null
    {
        return $this->booking->tour?->taxBracket();
    }

    public function getTaxes(): float|null
    {
        return $this->getTaxBracket()?->calculate($this->getTotalCost());
    }

    public function getBasePrice(): float
    {
        return $this->booking->tour?->base_price_per_person * $this->booking->travellers()->count();
    }

    public function addUnknownTraveller(): BookingTraveller
    {
        $traveller = $this->makeTraveller([
            'first_name' => 'Unknown',
            'last_name' => 'Traveller',
            'role' => BookingTravellerRole::UNKNOWN,
        ]);
        $traveller->save();
        // Primary traveller may not be lead booker
        $primary = $this->booking->travellers()->where('role', '=', BookingTravellerRole::NORMAL)->first();
        foreach (($primary?->repository->getComponents(false) ?? []) as $component) {
            $component->getTourComponent()->grantToBookingTraveller($traveller);
        }
        return $traveller;
    }

    public function removeUnknownTraveller(): void
    {
        $traveller = $this->booking->travellers()->where('role', '=', BookingTravellerRole::UNKNOWN)->first();
        $traveller?->repository->delete();
    }

    /**
     * Setup rooming with a specific hotel and room type
     * @param int|null $hotel The ID number of the hotel
     * @param array<array{room: int, travellers: int}> $rooming
     * @return void
     */
    public function setupSimpleRooming(int|null $hotel, array $rooming): void
    {
        if ($hotel === null) { return; }
        foreach ($rooming as $room) {
            if ($room['room'] === null || $room['travellers'] === null) { return; }
        }
        $this->wipeGroups();
        $this->booking->booking_accommodation_id = $hotel;
        $this->booking->saveQuietly();
        $key = -1;
        $group = null;
        foreach ($rooming as $i => $room) {
            $roomType = RoomType::find($room['room']);
            $room['travellers'] = min($room['travellers'], $roomType?->maximum_occupancy);
            $rooming[$i] = $room;
        }
        foreach ($this->booking->travellers()->where('role', '!=', BookingTravellerRole::NOT_TRAVELLING)->get() as $traveller) {
            if ($group === null || $rooming[$key]['travellers'] === 0) {
                $key++;
                if ($key >= count($rooming)) { break; }
                $group = BookingGroup::create(['booking_id' => $this->booking->id,]);
                foreach ($this->booking->tour->accommodationInventoryTours as $room) {
                    /** @noinspection TypeUnsafeComparisonInspection Type unsafe required. IDs are int, and code returns string */
                    if ($room->inventory->room_type_id == $rooming[$key]['room'] && $room->inventory->accommodation_id == $hotel) {
                        $group->repository->addRoomToGroup($room);
                    }
                }
            }
            if ($key >= count($rooming)) { break; }
            $group->repository->addTravellerToGroup($traveller);
            --$rooming[$key]['travellers'];
        }
    }

    /**
     * @throws RemoteGatewayError
     * @throws UnauthorizedGatewayException
     */
    public function getCheckoutLink(float $amount): string|null
    {
        $gateway = Gateway::getDefaultGateway();
        $item = new LineItem("Deposit for Booking from {$this->booking->leadTraveller->full_name}", $amount);
        $intention = PaymentIntention::build($this->booking->leadTraveller->customer, $this->booking->token, 'Deposit');

        $redirect = setting('booking.success.redirect', route('payment.gateway.stripe.success'));

        return $gateway?->checkout([$item,], $intention, $this->booking->leadTraveller, $redirect);
    }

    /**
     * @throws UnauthorizedGatewayException
     */
    public function getAirwallexKeys(float $amount): array|null
    {
        $gateway = Gateway::getPaymentGateway('airwallex');
        if (!($gateway instanceof AirwallexGateway)) {
            return null;
        }
        $item = new LineItem("Deposit for Booking from {$this->booking->leadTraveller->full_name}", $amount);
        $intention = PaymentIntention::build($this->booking->leadTraveller->customer, $this->booking->token, 'Deposit');

        $redirect = setting('booking.success.redirect', route('payment.gateway.stripe.success'));

        return $gateway?->getApiKeys([$item,], $intention, $this->booking->leadTraveller, $redirect);
    }

    public function getStripeKey(float $amount): string|null
    {
        $gateway = Gateway::getPaymentGateway('stripe');
        if (!($gateway instanceof StripeGateway)) {
            return null;
        }
        $item = new LineItem("Deposit for Booking from {$this->booking->leadTraveller->full_name}", $amount);
        $intention = PaymentIntention::build($this->booking->leadTraveller->customer, $this->booking->token, 'Deposit');

        $redirect = setting('booking.success.redirect', route('payment.gateway.stripe.success'));

        return $gateway?->getCheckoutSecret([$item,], $intention, $this->booking->leadTraveller, $redirect);
    }

    public function roundValue(float|null $amount, float|null $rate = null): float|null
    {
        if ($amount === null) { return null; }
        $rate = $rate ?? $this->getFXRate() ?? 1.0;
        $amount = sigfig($amount, $rate);
        if (flag('booking.round_to_five')) {
            $amount = round_to_five($amount);
        }
        return $amount;
    }

    public function getSimpleData(): array
    {
        $rate = $this->getFXRate() ?? 1.0;
        $travellers = [];
        foreach ($this->booking->travellers as $traveller) {
            $travellers[] = $traveller->repository->getData();
        }

        $data = [
            'token' => $this->booking->token,
            'url' => $this->booking->tour?->booking_form_url,
            'tour' => $this->booking->tour?->repository->getDataForBooking(),
            'lead' => [
                'first_name' => $this->booking->leadTraveller->first_name,
                'last_name' => $this->booking->leadTraveller->last_name,
                'email' => $this->booking->leadTraveller->email_address,
                'telephone' => $this->booking->leadTraveller->mobile_number,
            ],
            'finances' => [
                'currency' => $this->booking->currency?->code ?? Settings::currency()?->code,
                'base' => $this->roundValue($this->booking->tour->base_price_per_person, $rate),
                'package' => $this->roundValue($this->getBasePrice(), $rate),
                'upgrade' => $this->roundValue($this->getUpgradeCosts(), $rate),
                'surcharge' => $this->roundValue($this->getSingleOccupancyAmount(), $rate),
                'tax' => [
                    'name' => $this->getTaxBracket()?->name ?? 'No Taxes',
                    'percentage' => $this->getTaxBracket()?->rate,
                    'amount' => $this->roundValue($this->getTaxes(), $rate),
                ],
                'total' => $this->getTotalCost(),
                'due' => [
                    'deposit' => [
                        'percentage' => $this->booking->tour?->deposit_percentage,
                        'amount' => $this->roundValue(($this->booking->tour?->deposit_amount ?? 0.0) *
                            ($this->booking->travellers()->where('role', '!=', BookingTravellerRole::NOT_TRAVELLING)->count()), $rate),
                    ],
                    'amount' => $this->roundValue($this->getDueTodayAmount(), $rate),
                ]
            ],
            'travellers' => $travellers,
            'components' => [
                'rooming' => $this->getCurrentRoomingForApi(),
            ]
        ];

        if (bleeding_edge()) {
            $data = [
                'debug_url' => route('admin.booking.view', ['booking' => $this->booking,]),
                ...$data,
            ];
        }

        return $data;
    }

    public function getCurrentRoomingForApi(): array
    {
        $rooms = [];
        foreach ($this->booking->groups as $group) {
            $groupedRoom = GroupedHotelRooming::fromInventoryTour($group->accommodation->first()->tourComponent);
            $rooms[] = ['room' => $groupedRoom->getUniqueKey(), 'travellers' => $group->travellers->count(),];
        }
        return $rooms;
    }

    /**
     * @throws RoomingFailedException
     */
    public function processRoomingFromApi(array $information): void
    {
        /** @var array<string, GroupedHotelRooming> $requiredRooms */
        $requiredRooms = [];
        foreach ($this->booking->tour->repository->getHotelGroups() as $groups) {
            foreach ($groups as $group) {
                $key = $group->getUniqueKey();
                foreach ($information as $item) {
                    if ($item['room'] === $key) {
                        $requiredRooms[$key] = $group;
                    }
                }
            }
        }
        $travellers = clone $this->booking->travellers;
        $travellerRooms = [];
        foreach ($information as $item) {
            $room = $requiredRooms[$item['room']];
            $travellerCount = $item['travellers'];
            if ($travellerCount > $travellers->count()) {
                throw new RoomingFailedException('More travellers are assigned to rooms than are on the booking.');
            }
            if ($room->occupancy->maximum_occupancy < $travellerCount) {
                throw new RoomingFailedException('Too many travellers are assigned to a room.');
            }
            $travellersForRoom = [];
            for ($i = 0; $i < $travellerCount; $i++) {
                $traveller = $travellers->shift();
                $travellersForRoom[] = $traveller;
            }
            $travellerRooms[] = ['room' => $room, 'travellers' => $travellersForRoom];
        }
        $this->wipeGroups();
        foreach ($travellerRooms as $travellerRoom) {
            $group = BookingGroup::create(['booking_id' => $this->booking->id,]);
            foreach ($travellerRoom['travellers'] as $traveller) {
                $group->repository->addTravellerToGroup($traveller);
            }
            foreach ($travellerRoom['room']->rooms as $room) {
                $group->repository->addRoomToGroup($room);
            }
        }
    }

    public function getUpgradesForPackageDetails(): array
    {
        return [
            'rooms' => [
                ...$this->getRoomUpgrades(),
            ],
            'tickets' => [
                ...$this->getTicketUpgrades(),
            ],
            'inclusions' => [
                ...$this->getInclusionUpgrades(),
            ]
        ];
    }

    private function getRoomUpgrades(): array
    {
        $items = [];
        foreach ($this->booking->groups as $group) {
            $first = true;
            $cost = 0;
            $description = "";
            foreach ($group->accommodation as $room) {
                if ($first) {
                    $description = "{$room->tourComponent->inventory->component->name} ({$room->tourComponent->inventory->roomType->name})";
                    $first = false;
                }
                $cost += $room->tourComponent->tour_component_type === 'Included' ? 0 : $room->tourComponent->tour_sales_price;
            }
            if ($cost > 0) {
                $items[] = ['description' => $description, 'cost' => $cost];
            }
        }
        return $items;
    }

    private function getTicketUpgrades(): array
    {
        $upgradeCost = 0;
        $addonCost = 0;
        foreach ($this->booking->travellers as $traveller) {
            if ($traveller->role === BookingTravellerRole::NOT_TRAVELLING) { continue; }
            foreach ($traveller->activities as $activity) {
                if ($activity->tourComponent->inventory->component->activity_category !== ActivityCategory::MAIN) { continue; }
                if ($activity->tourComponent->tour_component_type === 'Add-on') {
                    $addonCost += $activity->tourComponent->tour_sales_price;
                } elseif ($activity->tourComponent->tour_component_type === 'Upgrade') {
                    $upgradeCost += $activity->tourComponent->tour_sales_price;
                }
            }
        }
        $items = [];
        if ($upgradeCost > 0) {
            $items[] = ['description' => 'Ticket Alterations', 'cost' => $upgradeCost];
        }
        if ($addonCost > 0) {
            $items[] = ['description' => 'Additional Ticket/s', 'cost' => $addonCost];
        }
        return $items;
    }

    private function getInclusionUpgrades(): array
    {
        $items = [];
        foreach ($this->booking->travellers as $traveller) {
            if ($traveller->role === BookingTravellerRole::NOT_TRAVELLING) { continue; }
            foreach ($traveller->activities as $activity) {
                if ($activity->tourComponent->inventory->component->activity_category !== ActivityCategory::NORMAL) { continue; }
                if ($activity->tourComponent->tour_component_type === 'Included') { continue; }
                $foundKey = null;
                foreach ($items as $key => $item) {
                    if ($item['id'] === 'activity-' . $activity->activity_inventory_tour_id) {
                        $foundKey = $key;
                        break;
                    }
                }
                if ($foundKey !== null) {
                    $arr = $items[$foundKey];
                    $arr['cost'] += $activity->tourComponent->tour_sales_price;
                    $items[$foundKey] = $arr;
                } else {
                    $items[] = [
                        'id' => 'activity-' . $activity->activity_inventory_tour_id,
                        'description' => $activity->tourComponent->inventory->component->name,
                        'cost' => $activity->tourComponent->tour_sales_price,
                    ];
                }
            }
            foreach ($traveller->merchandise as $merchandise) {
                if ($merchandise->tourComponent->tour_component_type === 'Included') { continue; }
                $foundKey = null;
                foreach ($items as $key => $item) {
                    if ($item['id'] === 'merchandise-' . $merchandise->merchandise_inventory_tour_id) {
                        $foundKey = $key;
                        break;
                    }
                }
                if ($foundKey !== null) {
                    $arr = $items[$foundKey];
                    $arr['cost'] += $merchandise->tourComponent->tour_sales_price;
                    $items[$foundKey] = $arr;
                } else {
                    $items[] = [
                        'id' => 'merchandise-' . $merchandise->merchandise_inventory_tour_id,
                        'description' => $merchandise->tourComponent->inventory->component->name,
                        'cost' => $merchandise->tourComponent->tour_sales_price,
                    ];
                }
            }
        }
        return $items;
    }

    public function getCurrency(): Currency|null
    {
        return $this->booking->currency ?? Settings::currency();
    }

    public function getFXRate()
    {
        return Settings::getConversionRate(Settings::currency(), $this->getCurrency());
    }

    public function validateStock(): bool
    {
        if (!$this->booking->tour->repository->hasEnoughStock($this->booking->travellers()->where('role', '!=', BookingTravellerRole::NOT_TRAVELLING)->count())) {
            return false;
        }
        $keys = [];
        foreach ($this->booking->travellers as $traveller) {
            if ($traveller->role === BookingTravellerRole::NOT_TRAVELLING) { continue; }
            foreach ($traveller->activities as $component) {
                if (!$component->tourComponent->repository->isStockControlActive()) { continue; }
                $key = 'activity-' . $component->activity_inventory_tour_id;
                if (!array_key_exists($key, $keys)) {
                    $keys[$key] = ['component' => $component->tourComponent, 'count' => 1,];
                } else {
                    $keys[$key]['count'] += 1;
                }
            }
            foreach ($traveller->flights as $component) {
                if (!$component->tourComponent->repository->isStockControlActive()) { continue; }
                $key = 'flight-' . $component->flight_inventory_tour_id;
                if (!array_key_exists($key, $keys)) {
                    $keys[$key] = ['component' => $component->tourComponent, 'count' => 1,];
                } else {
                    $keys[$key]['count'] += 1;
                }
            }
            foreach ($traveller->transport as $component) {
                if (!$component->tourComponent->repository->isStockControlActive()) { continue; }
                $key = 'transport-' . $component->transport_inventory_tour_id;
                if (!array_key_exists($key, $keys)) {
                    $keys[$key] = ['component' => $component->tourComponent, 'count' => 1,];
                } else {
                    $keys[$key]['count'] += 1;
                }
            }
            foreach ($traveller->merchandise as $component) {
                if (!$component->tourComponent->repository->isStockControlActive()) { continue; }
                $key = 'merchandise-' . $component->merchandise_inventory_tour_id;
                if (!array_key_exists($key, $keys)) {
                    $keys[$key] = ['component' => $component->tourComponent, 'count' => 1,];
                } else {
                    $keys[$key]['count'] += 1;
                }
            }
        }
        foreach ($this->booking->groups as $group) {
            if ($group->travellers->count() <= 0) { continue; }
            foreach ($group->accommodation as $component) {
                if (!$component->tourComponent->repository->isStockControlActive()) { continue; }
                $key = 'accommodation-' . $component->accommodation_inventory_tour_id;
                $travellers = $group->travellers()->where('role', '!=', BookingTravellerRole::NOT_TRAVELLING)->count();
                if (!array_key_exists($key, $keys)) {
                    $keys[$key] = ['component' => $component->tourComponent, 'count' => $travellers,];
                } else {
                    $keys[$key]['count'] += $travellers;
                }
            }
        }
        foreach ($keys as $key => $data) {
            if (!($data['component']->repository->hasEnoughStock($data['count']))) {
                \Log::info($key . ' is out of stock for ' . $data['count']);
                return false;
            }
        }
        return true;
    }
}
