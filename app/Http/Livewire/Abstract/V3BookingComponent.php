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
use App\Models\Quote\Quote;
use App\Models\System\Brand;
use App\Models\Tour\Tour;
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
    public BookingTraveller|null $lead = null;
    public bool $showCustomerForm = false;
    public $listeners = ['currencyUpdated' => 'updateCurrency'];

    public function mount(Tour|int|null $tour = null, Booking|int|null $booking = null, Quote|int|null $quote = null)
    {
        $this->tour = Tour::getForMount($tour);
        $this->booking = Booking::getForMount($booking);
        $this->quote = Quote::getForMount($booking->quote_id);
        $this->brand = $this->tour->brand ?? Brand::getSystemBrand();
        if ($this->booking->booking_accommodation_id  === null) {
            $hotels = $this->tour->repository->getHotels();
            if (count($hotels) > 0) { $this->selectedHotel = $hotels[array_key_first($hotels)]['hotel']->id; }
        } else {
            $this->selectedHotel  = $this->booking->booking_accommodation_id;
        }
        $this->selectedCurrency = $this->booking->currency?->code ?? setting('system.currency');
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
            $this->rooms[] = ['room' => $this->tour->repository->getDefaultRoom($this->selectedHotel), 'travellers' => 2,];
        }
       
        for ($i = count($this->rooms) - 1; $i >= $this->getMaximumRooms(); $i--) {
            unset($this->rooms[$i]);
        }

        $runSetup && $this->setupRooming();
    }

    public function setupRooming(): void
    {
        $this->booking->repository->setupSimpleRooming($this->selectedHotel, $this->rooms);
    }

    public function getTravellerCount(): int
    {
        return $this->booking->travellers()->where('role', '!=', BookingTravellerRole::NOT_TRAVELLING)->count();
    }

    public function getMinimumRooms(): int
    {
        return (int)ceil($this->getTravellerCount() / 2);
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
    }

    public function renew()
    {
        $this->booking = Booking::find($this->booking->id);
        $this->lead = $this->booking->leadTraveller;
        $this->tour = Tour::find($this->tour->id);
        /** @noinspection PhpSillyAssignmentInspection Seems to fix an issue with rooming caching */
        $this->rooms = $this->rooms;
    }
    
    public function toggleCustomerForm()
    {
        $this->showCustomerForm = !$this->showCustomerForm;
        if (!$this->showCustomerForm) {
            $this->resetErrorBag();
        }
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

    public function getCurrency(): Currency
    {
        return $this->booking->currency ?? Settings::currency();
    }

    public function getFXRate()
    {
        return Settings::getConversionRate(Settings::currency(), $this->getCurrency());
    }

    public function formatCurrency(float|int $value): string
    {
        return fr_currency($value * $this->getFXRate(), $this->getCurrency()) . " " . $this->getCurrency()->code;
    }

    public function hasActivity(ActivityInventoryTour $tourComponent): bool
    {
        return $this->booking->leadTraveller->activities()->where('activity_inventory_tour_id', '=', $tourComponent->id)->count() > 0;
    }

    public function adjustActivityUpgrade(int $upgradeId): void
    {
        $upgrade = ActivityInventoryTour::find($upgradeId);
        $parent = $upgrade->repository->getUpgradeParent();
        foreach ($this->booking->travellers as $traveller) {
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
    }

    public function toggleActivityAddon(int $id): void
    {
        $addon = ActivityInventoryTour::find($id);
        if ($addon !== null && $addon->tour_id === $this->tour->id && $addon->tour_component_type === 'Add-on') {
            $owned = $this->hasActivity($addon);
            // Not enough stock
            if (!$owned && $addon->available_stock < $this->booking->travellers()->count()) { return; }
            foreach ($this->booking->travellers as $traveller) {
                if (!$owned) {
                    $addon->repository->grantToBookingTraveller($traveller);
                } else {
                    $traveller->activities()->where('activity_inventory_tour_id', '=', $addon->id)->delete();
                }
            }
        }
    }
}
