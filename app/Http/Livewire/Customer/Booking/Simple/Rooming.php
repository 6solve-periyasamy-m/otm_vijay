<?php

namespace App\Http\Livewire\Customer\Booking\Simple;

use App\Http\Controllers\Customer\BookingV3Controller;
use App\Models\Accommodation\RoomType;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Helper\Enum\BookingTravellerRole;
use App\Models\Location\Currency;
use App\Models\Tour\Tour;
use App\Repository\Model\Booking\BookingRepository;
use App\Repository\Model\Booking\BookingTravellerRepository;
use Livewire\Component;
use Settings;

class Rooming extends Component
{
    private const MAX_TRAVELLERS = 5;

    protected $messages = [
        'rooms.*.room.required' => "This field is required",
        'rooms.*.travellers.required' => "This field is required",
    ];

    protected $listeners = ['currencyUpdated' => 'updateCurrency'];

    public Tour|int $tour;
    public int|null $selectedHotel = null;
    public Booking|int|null $booking;
    public BookingTraveller|null $lead = null;
    public array $rooms = [];
    public int $maxTravellers;

    public function mount(Tour|int $tour, Booking|int|null $booking = null, Currency|int|null $currency = null): void
    {
        $this->tour = Tour::getForMount($tour);
        $this->booking = Booking::getForMount($booking);

        if ($this->booking->tour_id !== null && $this->booking->tour_id !== $this->tour->id) { abort(404); }

        $this->maxTravellers = $this->tour->stock_control_active ? min($this->tour->repository->getAvailableStock(), self::MAX_TRAVELLERS) : self::MAX_TRAVELLERS;

        if ($this->booking->id === null) {
            $this->booking = BookingRepository::make($this->tour);
            $this->booking->currency_id = Currency::getForMount($currency)?->id;
            $this->booking->save();
        }

        $hotels = $this->tour->repository->getHotels();
        if (count($hotels) > 0) { $this->selectedHotel = $hotels[array_key_first($hotels)]['hotel']->id; }


        $this->lead = $this->booking->leadTraveller ?? BookingTravellerRepository::make([]);

        if ($this->lead->id === null) {
            $this->lead->booking_id = $this->booking->id;
            $this->lead->save();
            $this->booking->lead_traveller_id = $this->lead->id;
            $this->booking->save();
            $this->addTraveller();
        } else {
            $this->renewRooming();
        }
        $this->validateRoomCount();
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

    private function renewRooming(): void
    {
        if ($this->tour->accommodationInventoryTours()->count() === 0) { return; }
        foreach ($this->booking->groups as $group) {
            $type = $group->accommodation()->first()?->tourComponent->inventory->room_type_id ?? $this->tour->repository->getDefaultRoom($this->selectedHotel);
            $this->rooms[] = ['room' => $type, 'travellers' => $group->travellers()->count(),];
        }
    }

    public function getTravellerCount(): int
    {
        return $this->booking->travellers()->where('role', '!=', BookingTravellerRole::NOT_TRAVELLING)->count();
    }

    public function addTraveller(): void
    {
        if ($this->booking->travellers()->count() >= $this->maxTravellers) { return; }
        $this->booking->repository->addUnknownTraveller();
        $this->validateRoomCount();
        $this->renew();
        $this->render();
    }

    public function removeTraveller(): void
    {
        $this->booking->repository->removeUnknownTraveller();
        $this->validateRoomCount();
        $this->renew();
        $this->render();
    }

    public function setupRooming(): void
    {
        if ($this->tour->accommodationInventoryTours()->count() === 0) { return; }
        $this->booking->repository->setupSimpleRooming($this->selectedHotel, $this->rooms);
    }

    public function proceed()
    {
        $this->validate();
        $this->lead->save();
        $this->booking->lead_traveller_id = $this->lead->id;
        $this->booking->save();
        if ($this->tour->accommodationInventoryTours()->count() > 0) {
            $travellerExcess = $this->getTravellerCount();
            foreach ($this->rooms as $room) {
                $travellerExcess -= RoomType::find($room['room'])?->maximum_occupancy;
            }
            if ($travellerExcess > 0) {
               return $this->addError('common', 'Not all travellers have rooms');
            }
            if ($travellerExcess < 0) {
               return $this->addError('common', 'More travellers have been added to rooms than are travelling');
            }
            if (!$this->booking->repository->validateStock()) {
                $bEmail = $this->booking->tour->brand->email;
                return $this->addError('common', "Some components in this package are out-of-stock. Please contact us at {$bEmail} for alternative options.");
            }
        }
        return redirect()->route('booking.simple.checkout', [
            'token' => $this->booking->token,
            'tour' => $this->tour->booking_form_url,
        ]);
    }

    public function updated($name, $value): void
    {
        $this->validateOnly($name);
        $this->booking->save();
        $this->lead->save();
        $this->validateRoomCount();
        $this->renew();
        $this->render();
    }

    public function addRoom(): void
    {
        if (count($this->rooms) >= $this->getMaximumRooms()) { return; }
        $room = $this->tour->repository->getDefaultRoom($this->selectedHotel);
        $this->rooms[] = ['room' => $room, 'travellers' => RoomType::find($room)?->maximum_occupancy,];
    }

    public function removeRoom(): void
    {
        if ((count($this->rooms) - 1) < $this->getMinimumRooms()) { return; }
        unset($this->rooms[count($this->rooms) - 1]);
    }

    public function validateRoomCount(): void
    {
        if ($this->tour->accommodationInventoryTours()->count() === 0) { return; }
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

    public function getMinimumRooms(): int
    {
        return (int)ceil($this->getTravellerCount() / 2);
    }

    public function getMaximumRooms(): int
    {
        return $this->getTravellerCount();
    }

    public function render()
    {
        return view('livewire.customer.booking.simple.rooming');
    }

    // public function rules()
    // {
    //     return [
    //         'lead.first_name' => 'required|string',
    //         'lead.last_name' => 'required|string',
    //         'lead.email_address' => 'required|email:rfc,dns',
    //         'lead.mobile_number' => 'required|phone:INTERNATIONAL',
    //         'rooms.*.room' => 'required|integer',
    //         'rooms.*.travellers' => 'required|integer|min:1',
    //     ];
    // }
    public function rules()
    {
        if ($this->tour->accommodationInventoryTours()->count() > 0) {
            return [
                'lead.email_address' => 'required|email:rfc,dns',
                'rooms.*.room' => 'required|integer',
                'selectedHotel' => 'required|integer',
                //'rooms.*.travellers' => 'required|integer|min:1',
            ];
        }

        return [
            'lead.email_address' => 'required|email:rfc,dns',
            'rooms.*.room' => 'nullable|integer',
            'selectedHotel' => 'nullable|integer',
            //'rooms.*.travellers' => 'required|integer|min:1',
        ];
    }

    public function getCurrency()
    {
        return $this->booking->currency ?? Settings::currency();
    }

    public function getFXRate(): float
    {
        return Settings::getConversionRate(Settings::currency(), $this->getCurrency()) ?? 1.0;
    }

    public function formatCurrency(int|float|null $value, bool $round = true): string
    {
        $value = $value ?? 0.0;
        $value *= $this->getFXRate();
        if ($round && flag('booking.round_to_five')) {
            $value = round_to_five($value);
        }
        return fr_currency($value, $this->getCurrency(), true) . " " . $this->getCurrency()->code;
    }

    public function updateCurrency(string $currency): void
    {
        if (in_array(strtoupper($currency), BookingV3Controller::ALLOWED_CURRENCIES)) {
            $this->booking->currency_id = Currency::where('code', $currency)->first()?->id ?? Settings::currency()?->id;
            $this->booking->repository->updateCurrency($currency);
            $this->renew();
        }
    }
}
