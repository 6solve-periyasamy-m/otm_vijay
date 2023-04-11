<?php

namespace App\Repository\Storage;

use Carbon\Carbon;

class ComponentInformation
{
    public string $name;
    public string $description;
    public string|null $image;
    public Carbon|null $start;
    public Carbon|null $end;
    /**
     * @var array<string, string> $attributes
     */
    public array $attributes;

    /**
     * @param string $name
     * @param string $description
     * @param string|null $image
     * @param Carbon|null $start
     * @param Carbon|null $end
     * @param array $attributes
     */
    public function __construct(string $name, string $description, string|null $image, Carbon|null $start, Carbon|null $end, array $attributes = [])
    {
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
        $this->start = $start;
        $this->end = $end;
        $this->attributes = $attributes;
    }
}
