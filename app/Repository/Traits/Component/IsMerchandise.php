<?php

namespace App\Repository\Traits\Component;

trait IsMerchandise
{
    public function getComponentType(): string
    {
        return "merchandise";
    }
}
