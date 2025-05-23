<?php

namespace App\Repository\Interfaces\Manifest;

use App\Models\Order\Component\OrderMerchandise;
use Illuminate\Support\Collection;

interface HasMerchandiseManifest
{
    /**
     * @return Collection|OrderMerchandise[]
     */
    public function getMerchandiseManifest(): Collection|array;
}
