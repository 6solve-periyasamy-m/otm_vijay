<?php

namespace App\Repository\Traits\Component;

trait IsFlight
{
    public function getComponentType(): string
    {
        return "flight";
    }
}
