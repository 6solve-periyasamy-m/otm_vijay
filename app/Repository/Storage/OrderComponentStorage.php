<?php

namespace App\Repository\Storage;

use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;

class OrderComponentStorage
{
    /**
     * @var OrderActivity[]
     */
    public array $activities;
    /**
     * @var OrderFlight[]
     */
    public array $flights;
    /**
     * @var OrderTransport[]
     */
    public array $transport;
    /**
     * @var OrderMerchandise[]
     */
    public array $merchandise;

    /**
     * @param OrderActivity[] $activities
     * @param OrderFlight[] $flights
     * @param OrderTransport[] $transport
     * @param OrderMerchandise[] $merchandise
     */
    public function __construct(array $activities, array $flights, array $transport, array $merchandise) {
        $this->activities = $activities;
        $this->flights = $flights;
        $this->transport = $transport;
        $this->merchandise = $merchandise;
    }

    public function clone(): OrderComponentStorage
    {
        $activities = [];
        $flights = [];
        $transport = [];
        $merchandise = [];
        foreach ($this->activities as $model) {
            $activities[]  = OrderActivity::make($model->attributesToArray());
        }
        foreach ($this->flights as $model) {
            $flights[]  = OrderFlight::make($model->attributesToArray());
        }
        foreach ($this->transport as $model) {
            $transport[]  = OrderTransport::make($model->attributesToArray());
        }
        foreach ($this->merchandise as $model) {
            $merchandise[]  = OrderMerchandise::make($model->attributesToArray());
        }
        return new self($activities, $flights, $transport, $merchandise);
    }
}