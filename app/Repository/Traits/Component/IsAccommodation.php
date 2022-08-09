<?php

namespace App\Repository\Traits\Component;

trait IsAccommodation
{
    public function getComponentType(): string
    {
        return "accommodation";
    }
}
