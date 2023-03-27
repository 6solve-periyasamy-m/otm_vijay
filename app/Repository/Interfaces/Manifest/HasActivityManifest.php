<?php

namespace App\Repository\Interfaces\Manifest;

use App\Models\Order\Component\OrderActivity;
use Illuminate\Support\Collection;

interface HasActivityManifest
{
    /**
     * @return Collection|OrderActivity[]
     */
    public function getActivityManifest(): Collection|array;
}
