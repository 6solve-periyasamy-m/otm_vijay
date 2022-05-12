<?php

namespace App\Repository\Abstracts;

abstract class OrderComponentRepository extends ModelRepository
{
    public abstract function getTourComponentType(): string;
    public abstract function getCost(): float;
}
