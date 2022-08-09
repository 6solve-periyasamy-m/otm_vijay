<?php

namespace App\Repository\Traits\Component;

trait IsTransport
{
    public function getComponentType(): string
    {
        return "transport";
    }
}
