<?php

namespace App\Repository\Interfaces\Manifest;

use App\Models\Order\Order;
use Illuminate\Support\Collection;

interface HasOrderManifest
{
    /**
     * @return Collection|Order[]
     */
    public function getOrderManifest(): Collection|array;
}
