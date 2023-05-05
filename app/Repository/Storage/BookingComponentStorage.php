<?php

namespace App\Repository\Storage;

use App\Models\Booking\Component\BookingActivity;
use App\Models\Booking\Component\BookingFlight;
use App\Models\Booking\Component\BookingMerchandise;
use App\Models\Booking\Component\BookingTransport;

class BookingComponentStorage
{
    /**
     * @var BookingActivity[]
     */
    public array $activities;
    /**
     * @var BookingFlight[]
     */
    public array $flights;
    /**
     * @var BookingTransport[]
     */
    public array $transport;
    /**
     * @var BookingMerchandise[]
     */
    public array $merchandise;

    /**
     * @param BookingActivity[] $activities
     * @param BookingFlight[] $flights
     * @param BookingTransport[] $transport
     * @param BookingMerchandise[] $merchandise
     */
    public function __construct(array $activities = [], array $flights = [], array $transport = [], array $merchandise = []) {
        $this->activities = $activities;
        $this->flights = $flights;
        $this->transport = $transport;
        $this->merchandise = $merchandise;
    }

    public function clone(): BookingComponentStorage
    {
        $activities = [];
        $flights = [];
        $transport = [];
        $merchandise = [];
        foreach ($this->activities as $model) {
            $activities[]  = BookingActivity::make($model->attributesToArray());
        }
        foreach ($this->flights as $model) {
            $flights[]  = BookingFlight::make($model->attributesToArray());
        }
        foreach ($this->transport as $model) {
            $transport[]  = BookingTransport::make($model->attributesToArray());
        }
        foreach ($this->merchandise as $model) {
            $merchandise[]  = BookingMerchandise::make($model->attributesToArray());
        }
        return new self($activities, $flights, $transport, $merchandise);
    }
}
