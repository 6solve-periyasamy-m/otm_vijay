<?php

namespace App\Repository\Traits\Component;

trait IsActivity
{
    public function getComponentType(): string
    {
        return "activity";
    }
}
