<?php

namespace App\Repository\Storage;

use Carbon\Carbon;
use Closure;
use Illuminate\View\View;

class ComponentInformation
{
    public string $name;
    public string|null $description;
    public string $upgrade_name;
    public string|null $image;
    public string|null $notes;
    public Carbon|null $start;
    public Carbon|null $end;
    public View|string|Closure $icon;
    /**
     * @var array<string, string> $attributes
     */
    public array $attributes;

    /**
     * @param string $name
     * @param string|null $description
     * @param string|null $image
     * @param Carbon|null $start
     * @param Carbon|null $end
     * @param View|string|Closure $icon
     * @param string|null $notes
     * @param string $upgrade_name
     * @param array $attributes
     */
    public function __construct(string $name, string|null $description, string|null $image, Carbon|null $start, Carbon|null $end, View|string|Closure $icon, string|null $notes = "", string $upgrade_name = "", array $attributes = [])
    {
        $this->name = $name;
        $this->description = $description;
        $this->upgrade_name = $upgrade_name;
        $this->image = $image;
        $this->start = $start;
        $this->end = $end;
        $this->icon = $icon;
        $this->attributes = $attributes;
        $this->notes = $notes;
    }
}
