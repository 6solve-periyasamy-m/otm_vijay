<?php

namespace App\Repository\Interfaces\Manifest;

use App\Models\Order\Component\OrderTransport;
use Illuminate\Support\Collection;

interface HasTransportManifest
{
    /**
     * @return Collection|OrderTransport[]
     */
    public function getTransportManifest(): Collection|array;
}
