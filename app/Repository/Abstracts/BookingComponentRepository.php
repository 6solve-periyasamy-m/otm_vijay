<?php

namespace App\Repository\Abstracts;

abstract class BookingComponentRepository
{
    public abstract function getTourComponentType(): string;
    public abstract function getCost(): float;
}