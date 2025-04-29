<?php

namespace App\Http\Livewire\Abstract;

use App\Models\Booking\Booking;
use App\Models\System\Brand;
use App\Models\Tour\Tour;
use Livewire\Component;
use App\Models\Helper\Enum\BookingTravellerRole;
use App\Models\Booking\BookingTraveller;
use App\Models\Quote\Quote;

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
        $this->selectedCurrency = $this->booking->booking_currency ?? setting('system.currency');
    }

    abstract public function back();
    abstract public function advance();

    public function getDefaultHotel()
    {
        foreach ($this->tour->repository->getHotels() as $hotel) {
            return $hotel['hotel'];
        }
        return null;
    }

    public function validateRoomCount(): void
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

        $this->setupRooming();
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

    public function updateCurrency(string $currency)
    {
        $this->selectedCurrency = $currency;
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
    
}
