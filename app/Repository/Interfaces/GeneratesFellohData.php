<?php

namespace App\Repository\Interfaces;

interface GeneratesFellohData
{
    public function getFellohData(): array;

    public function getReference(): string;

    public function getFellohId(): string|null;

    public function setFellohId(string $id): void;
}