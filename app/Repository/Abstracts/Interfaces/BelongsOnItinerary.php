<?php

namespace App\Repository\Abstracts\Interfaces;

use App\Repository\Storage\Itinerary\ItineraryItem;

interface BelongsOnItinerary
{
    public function getItineraryItem(): ItineraryItem;
}
