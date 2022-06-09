<?php

namespace App\Repository\Model\Booking;

use App\Exceptions\NotOnTourException;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ModelRepository;

class BookingRepository extends ModelRepository
{
    private Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public static function create(Tour $tour, BookingTraveller $leadTraveller): Booking
    {
        do {
            $token = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(64/strlen($x)) )),1,64);
            $booking = Booking::where('token', $token)->first();
        } while (isset($booking));
        $booking = Booking::create(['token' => $token, 'tour_id' => $tour->id]);
        $booking->travellers()->save($leadTraveller);
        $booking->lead_traveller_id = $leadTraveller->id;
        $booking->save();
        return $booking;
    }

    public function addIncludedToAll(): void
    {
        $components = $this->booking->tour->repository->getComponents(false, true, false, true, true, ['Included',]);
        foreach ($this->booking->travellers as $traveller) {
            $traveller->repository->addComponents($components);
        }
    }

    public function getTotalCost(): float
    {
        $cost = 0;
        foreach ($this->booking->travellers as $traveller) { $cost += $traveller->total_cost; }
        return $cost;
    }

    public function getDueTodayAmount(): float
    {
        return $this->booking->tour->deposit * $this->booking->travellers()->count();
    }

    public function getSingleOccupancyCount(): int
    {
        $count = 0;
        foreach ($this->booking->travellers as $traveller) { $count += $traveller->has_single_occupancy; }
        return $count;
    }

    public function getSingleOccupancyAmount(): float
    {
        $cost = 0;
        foreach ($this->booking->travellers as $traveller) { $cost += $traveller->surcharge_amount; }
        return $cost;
    }

    /**
     * @throws NotOnTourException
     */
    public function selectFlights(int $inbound, int $outbound)
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

    public function get(): Booking
    {
        return $this->booking;
    }

    public function update(array $data): Booking
    {
        $this->booking->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->booking->save();
    }

    public function delete(): bool
    {
        return $this->booking->delete();
    }

    public function isDeleted(): bool
    {
        return !isset($this->booking);
    }

    public function __toString(): string
    {
        return "{{$this->booking->token}} - {$this->booking->tour->name}";
    }
}