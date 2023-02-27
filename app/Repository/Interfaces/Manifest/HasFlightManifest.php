<?php

namespace App\Repository\Interfaces\Manifest;

use App\Models\Order\Component\OrderFlight;
use Illuminate\Support\Collection;

interface HasFlightManifest
{
    /**
     * @return Collection|OrderFlight[]
     */
    public function getFlightManifest(): Collection|array;
}
