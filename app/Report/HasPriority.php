<?php

namespace App\Report;

trait HasPriority
{
    public function getPriority(): int
    {
        return parent::getPriority() + 1;
    }
}