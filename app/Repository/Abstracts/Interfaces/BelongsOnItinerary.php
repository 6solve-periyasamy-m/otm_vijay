<?php

namespace App\Repository\Abstracts\Interfaces;

use App\Repository\Storage\Order\ItineraryItem;

interface BelongsOnItinerary
{
    public function getItineraryItem(): ItineraryItem;
}
