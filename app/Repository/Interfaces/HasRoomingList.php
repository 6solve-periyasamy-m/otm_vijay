<?php

namespace App\Repository\Interfaces;

use App\Models\Order\Component\OrderAccommodation;
use Illuminate\Support\Collection;

interface HasRoomingList
{
    /**
     * @return Collection|OrderAccommodation[]
     */
    public function getRoomingList(): Collection|array;
}
