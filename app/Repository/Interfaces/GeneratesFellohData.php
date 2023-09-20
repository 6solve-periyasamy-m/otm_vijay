<?php

namespace App\Repository\Interfaces;

interface GeneratesFellohData
{
    public function getFellohData(): array;

    public function getReference(): string;
}