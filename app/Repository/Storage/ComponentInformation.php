<?php

namespace App\Repository\Storage;

use Carbon\Carbon;

class ComponentInformation
{
    public string $name;
    public string $description;
    public string $upgrade_name;
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
     * @param string $upgrade_name
     * @param array $attributes
     */
    public function __construct(string $name, string $description, string|null $image, Carbon|null $start, Carbon|null $end, string $upgrade_name = "", array $attributes = [])
    {
        $this->name = $name;
        $this->description = $description;
        $this->upgrade_name = $upgrade_name;
        $this->image = $image;
        $this->start = $start;
        $this->end = $end;
        $this->attributes = $attributes;
    }
}
