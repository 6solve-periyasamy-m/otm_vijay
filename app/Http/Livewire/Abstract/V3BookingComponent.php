<?php

namespace App\Http\Livewire\Abstract;

use App\Exceptions\MailDisabledException;
use App\Exceptions\MailFailedException;
use App\Mail\Storage\Attachment;
use App\Mail\Storage\QuoteMail;
use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Helper\Enum\BookingTravellerRole;
use App\Models\Location\Currency;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Quote\Quote;
use App\Models\System\Brand;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\InventoryTourRepository;
use App\Models\Helper\Enum\ActivityCategory;
use Exception;
use Livewire\Component;
use Settings;

abstract class V3BookingComponent extends Component
{
    public Tour|int|null $tour;
    public Booking|int|null $booking;
    public Quote|int|null $quote;
    public Brand $brand;
    public int|null $selectedHotel;
    public string $selectedCurrency;
    public array $rooms = [];
    public bool $payFull = false;
    public BookingTraveller|null $lead = null;
    public bool $showCustomerForm = false;
    public $listeners = ['currencyUpdated' => 'updateCurrency'];

    public function mount(Tour|int|null $tour = null, Booking|int|null $booking = null, Quote|int|null $quote = null)
    {
        $this->tour = Tour::getForMount($tour);
        $this->booking = Booking::getForMount($booking);
        $this->quote = Quote::getForMount($booking->quote_id);
        $this->brand = $this->tour->brand ?? Brand::getSystemBrand();
        $this->payFull = $this->booking->pay_full ?? false;
        if ($this->booking->booking_accommodation_id  === null) {
            $groupedHotel = $this->tour->repository->getDefaultHotelGroup();
            if ($groupedHotel !== null) { $this->selectedHotel = $groupedHotel->hotel->id; }
        } else {
            $this->selectedHotel  = $this->booking->booking_accommodation_id;
        }
        $this->selectedCurrency = $this->booking->currency?->code ?? setting('system.currency');
    }

    /**
     *  Get the number of passengers who are actually travelling
     */
    public function getTravellingCount(): int
    {
        return $this->booking->travellers()->where('role', '!=', BookingTravellerRole::NOT_TRAVELLING)->count();
    }

    abstract public function back();
    abstract public function advance();

    public function getDefaultHotel(): AccommodationInventory|null
    {
        return $this->tour->accommodationInventoryTours()->where('tour_component_type', '=', 'Included')->first()?->inventory;
    }

    public function getSelectedHotel(): Accommodation|null
    {
        return Accommodation::find($this->selectedHotel);
    }

    public function validateRoomCount(bool $runSetup = true): void
    {
        foreach ($this->rooms as $i => $iValue) {
            $this->rooms[$i]['travellers'] = (int)$iValue['travellers'];
        }

        for ($i = count($this->rooms); $i < $this->getMinimumRooms(); $i++) {
            $this->rooms[] = ['room' => null, 'travellers' => null,];
        }
       
        for ($i = count($this->rooms) - 1; $i >= $this->getMaximumRooms(); $i--) {
            unset($this->rooms[$i]);
        }

        $runSetup && $this->setupRooming();
        $this->renew();
    }

    public function setupRooming(): void
    {
        $this->booking->repository->setupSimpleRooming($this->selectedHotel, $this->rooms);
        $this->renew();
    }

    public function getTravellerCount(): int
    {
        return $this->booking->travellers()->where('role', '!=', BookingTravellerRole::NOT_TRAVELLING)->count();
    }

    public function getMinimumRooms(): int
    {
        $maxOccupancy = 0;
        foreach ($this->tour->accommodationInventoryTours as $tourComponent) {
            if ($maxOccupancy < $tourComponent->inventory->roomType->maximum_occupancy) {
                $maxOccupancy = $tourComponent->inventory->roomType->maximum_occupancy;
            }
        }
        return (int)ceil($this->getTravellerCount() / $maxOccupancy);
    }

    public function getMaximumRooms(): int
    {
        return $this->getTravellerCount();
    }

    public function updateCurrency(string $currency): void
    {
        $this->selectedCurrency = $currency;
        $this->booking->currency_id = Currency::where('code', $currency)->first()?->id ?? Settings::currency()?->id;
        $this->booking->repository->updateCurrency($currency);
        $this->renew();
    }

    public function renew()
    {
        $this->booking = Booking::find($this->booking->id);
        $this->lead = $this->booking->leadTraveller;
        $this->tour = Tour::find($this->tour->id);
        /** @noinspection PhpSillyAssignmentInspection Seems to fix an issue with rooming caching */
        $this->rooms = $this->rooms;
        $this->render();
    }
    
    public function toggleCustomerForm()
    {
        $this->showCustomerForm = !$this->showCustomerForm;
        if (!$this->showCustomerForm) {
            $this->resetErrorBag();
        }
        $this->renew();
    }

    public function emailQuote()
    {
        if ($this->booking->leadTraveller->email_address === null) {
            session()->flash('error', 'Cannot send quote, no valid target email found.');
        }
        try {
            if ($this->booking->quote_id !== null) {
                $quote = Quote::find($this->booking->quote_id);
                $sent = $quote->repository->generateSent($this->booking->leadTraveller->email_address, $quote->paying?? 1, $quote->travelling?? 0);
                $attachment = new Attachment($quote->repository->getStream($sent), $quote->reference . '.pdf', ['mime' => 'application/pdf',]);
                $status = (new QuoteMail('quote', $quote->consultant))->send($target ?? $sent->recipient, $sent, [$attachment,], "", true);
                $this->showCustomerForm = false;
                session()->flash('success', 'Quote emailed successfully!');
            } else {
                session()->flash('error', 'Failed to Send Quote - Quote not found.');
                return;
            }            
        } catch (MailDisabledException) {
            session()->flash('error', 'Failed to Send Quote-Emails are not enabled on this system.');
            return;
        } catch (MailFailedException $e) {
            $status = false;
        } catch (Exception $e) {
            session()->flash('error', 'Failed to Send Quote-'.$e->getMessage());
            return;
        }
        if ($status === true) {
            session()->flash('error', 'Quote emailed successfully.');
        } else {
            session()->flash('error', 'Cannot send quote, no valid target email found.');
        }
    }

    abstract public function render();

    public function getCurrency(): Currency
    {
        return $this->booking->currency ?? Settings::currency();
    }

    public function getFXRate()
    {
        return Settings::getConversionRate(Settings::currency(), $this->getCurrency());
    }

    public function formatCurrency(float|int|null $value, ?int $decimalPrecision = 0): string
    {
        $value = $value ?? 0.0;
        $value *= $this->getFXRate();
        if (flag('booking.round_to_five')) {
            $value = round_to_five($value);
        }
        return fr_currency($value, $this->getCurrency(), true, $decimalPrecision) . " " . $this->getCurrency()->code;
    }

    public function hasActivity(ActivityInventoryTour $tourComponent): bool
    {
        return $this->booking->leadTraveller->activities()->where('activity_inventory_tour_id', '=', $tourComponent->id)->count() > 0;
    }

    public function hasMerchandise(MerchandiseInventoryTour $tourComponent): bool
    {
        return $this->booking->leadTraveller->merchandise()->where('merchandise_inventory_tour_id', '=', $tourComponent->id)->count() > 0;
    }

    public function payFull()
    {
        $this->booking->pay_full = true;
        $this->booking->save();
        $this->payFull = true;
    }

    public function payDueToday()
    {
        $this->booking->pay_full = false;
        $this->booking->save();
        $this->payFull = false;
    }

    public function adjustActivityUpgrade(int $upgradeId): void
    {
        $upgrade = ActivityInventoryTour::find($upgradeId);
        $parent = $upgrade->repository->getUpgradeParent();
        foreach ($this->booking->travellers as $traveller) {
            if ($traveller->role === BookingTravellerRole::NOT_TRAVELLING) continue;
            $found = false;
            foreach ($traveller->activities as $activity) {
                // already owns component
                if ($activity->activity_inventory_tour_id === $upgrade->id) { $found = true; break; }
                // Is already on tree
                if ($parent->repository->hasAsUpgrade($activity->tourComponent)) {
                    $activity->repository->delete();
                    $upgrade->repository->grantToBookingTraveller($traveller);
                    $found = true;
                    break;
                }
            }
            // If upgrade isn't found, then add it anyway
            if (!$found) {
                $upgrade->repository->grantToBookingTraveller($traveller);
            }
        }
        $this->renew();
    }

    public function adjustMerchandiseUpgrade(int $upgradeId): void
    {
        $upgrade = MerchandiseInventoryTour::find($upgradeId);
        $parent = $upgrade->repository->getUpgradeParent();
        foreach ($this->booking->travellers as $traveller) {
            if ($traveller->role === BookingTravellerRole::NOT_TRAVELLING) continue;
            $found = false;
            foreach ($traveller->merchandise as $component) {
                // already owns component
                if ($component->merchandise_inventory_tour_id === $upgrade->id) { $found = true; break; }
                // Is already on tree
                if ($parent->repository->hasAsUpgrade($component->tourComponent)) {
                    $component->repository->delete();
                    $upgrade->repository->grantToBookingTraveller($traveller);
                    $found = true;
                    break;
                }
            }
            // If upgrade isn't found, then add it anyway
            if (!$found) {
                $upgrade->repository->grantToBookingTraveller($traveller);
            }
        }
        $this->renew();
    }

    public function toggleActivityAddon(int $id): void
    {
        $addon = ActivityInventoryTour::find($id);
        if ($addon !== null && $addon->tour_id === $this->tour->id && $addon->tour_component_type === 'Add-on') {
            $owned = $this->hasActivity($addon);
            // Not enough stock
            if (!$owned && !$addon->repository->hasEnoughStock($this->getTravellingCount())) { return; }
            foreach ($this->booking->travellers as $traveller) {
                if ($traveller->role === BookingTravellerRole::NOT_TRAVELLING) continue;
                if (!$owned) {
                    $addon->repository->grantToBookingTraveller($traveller);
                } else {
                    $traveller->activities()->where('activity_inventory_tour_id', '=', $addon->id)->delete();
                }
            }
        }
        $this->renew();
    }

    public function toggleMerchandiseAddon(int $id): void
    {
        $addon = MerchandiseInventoryTour::find($id);
        if ($addon !== null && $addon->tour_id === $this->tour->id && $addon->tour_component_type === 'Add-on') {
            $owned = $this->hasMerchandise($addon);
            // Not enough stock
            if (!$owned && !$addon->repository->hasEnoughStock($this->getTravellingCount())) { return; }
            foreach ($this->booking->travellers as $traveller) {
                if ($traveller->role === BookingTravellerRole::NOT_TRAVELLING) continue;
                if (!$owned) {
                    $addon->repository->grantToBookingTraveller($traveller);
                } else {
                    $traveller->merchandise()->where('merchandise_inventory_tour_id', '=', $addon->id)->delete();
                }
            }
        }
        $this->renew();
    }

    public function increaseAddonCount(string $type, int $id): void
    {
        $component = InventoryTourRepository::getComponent($type, $id);
        if ($component !== null) {
            $this->setAddonQuantity($component, $component->getQuantityOnBooking($this->booking) + 1);
        }
    }

    public function decreaseAddonCount(string $type, int $id): void
    {
        $component = InventoryTourRepository::getComponent($type, $id);
        if ($component !== null) {
            $this->setAddonQuantity($component, $component->getQuantityOnBooking($this->booking) - 1);
        }
    }

    public function getAddonCount(string $type, int $id): int
    {
        return InventoryTourRepository::getComponent($type, $id)?->getQuantityOnBooking($this->booking) ?? 0;
    }

    private function setAddonQuantity(InventoryTourRepository $repository, int $count): void
    {
        $repository->removeFromAllTravellers($this->booking);
        if ($repository->isStockControlActive()) {
            $count = min(max($repository->getAvailableStock(), 0), $count);
        }
        foreach ($this->booking->travellers as $traveller) {
            if ($traveller->role === BookingTravellerRole::NOT_TRAVELLING) { continue;}
            if ($count > 0) {
                $repository->grantToBookingTraveller($traveller);
                $count--;
            } else {
                break;
            }
        }
        $this->renew();
    }

    public function getStepsProperty()
    {
        $steps = [
            ['label' => 'Guests', 'route' => 'booking.v3.guest'],
            ['label' => 'Accommodation', 'route' => 'booking.v3.hotel'],
            ['label' => 'Ticket(s)', 'route' => 'booking.v3.tickets'],
        ];
        if ($this->hasInclusions()) {
            $steps[] = ['label' => 'Additional Inclusions', 'route' => 'booking.v3.inclusions'];
        }
        $steps[] = ['label' => 'Details', 'route' => 'booking.v3.details'];
        $steps[] = ['label' => 'Confirmation', 'route' => 'booking.v3.confirmation'];
        return collect($steps)->mapWithKeys(fn($step, $i) => [$i + 1 => $step]);
    }

    public function hasInclusions(): bool
    {
        // 1. Included activities (NORMAL)
        $hasValidIncludedActivities = $this->tour->activityInventoryTours()
            ->where('tour_component_type', 'Included')
            ->get()
            ->contains(fn($component) =>
                $component->inventory->component->activity_category === ActivityCategory::NORMAL
            );
        // 2. Add-on activities (NORMAL & available stock)
        $hasValidAddonActivities = $this->tour->activityInventoryTours()
            ->where('tour_component_type', 'Add-on')
            ->get()
            ->contains(fn($component) =>
                $component->inventory->component->activity_category === ActivityCategory::NORMAL
                && $component->repository->getAvailableStock() > 0
            );

        // 3. Included merchandise
        $hasIncludedMerchandise = $this->tour->merchandise()
            ->where('tour_component_type', 'Included')
            ->exists();

        // 4. Add-on merchandise with stock
        $hasValidAddonMerchandise = $this->tour->merchandise()
            ->where('tour_component_type', 'Add-on')
            ->get()
            ->contains(fn($component) =>
                $component->repository->getAvailableStock() > 0
            );

        return $hasValidIncludedActivities
            || $hasValidAddonActivities
            || $hasIncludedMerchandise
            || $hasValidAddonMerchandise;
    }
}
