<?php

namespace Tests\Traits\Model;

use App\Models\Merchandise\Merchandise;
use App\Models\Merchandise\MerchandiseInventory;

trait TestsMerchandise
{
    function generateMerchandise(): Merchandise
    {
        return Merchandise::factory()->create();
    }

    function generateMerchandiseInventory(Merchandise $merchandise = null, array $attributes = []): MerchandiseInventory
    {
        $merchandise = $merchandise ?? $this->generateMerchandise();
        $inventory = MerchandiseInventory::factory()->make($attributes);
        $merchandise->inventory()->save($inventory);
        return $inventory;
    }
}
