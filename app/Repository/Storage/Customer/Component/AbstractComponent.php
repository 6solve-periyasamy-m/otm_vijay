<?php

namespace App\Repository\Storage\Customer\Component;

use App\Repository\Abstracts\InventoryTourRepository;
use App\Repository\Storage\ComponentInformation;
use App\Repository\Traits\HasCustomAttributes;
use Carbon\Carbon;
use Closure;
use Illuminate\View\View;

/**
 * @property string $name
 * @property string $description
 * @property string $tour_component_type
 * @property string $upgrade_name
 * @property string|null $image
 * @property Carbon|null $start
 * @property Carbon|null $end
 * @property string|null $notes
 * @property View|string|Closure $icon
 * @property array<string, string> $attributes
 */
abstract class AbstractComponent
{
    use HasCustomAttributes;

    public readonly InventoryTourRepository $component;
    private ComponentInformation $information;

    public function __construct(InventoryTourRepository $component)
    {
        $this->component = $component;
        $this->information = $component->getComponentInformation();
    }

    public function getTourComponentTypeAttribute(): string
    {
        return $this->component->getTourComponentType();
    }

    public function getNameAttribute(): string
    {
        return $this->information->name;
    }

    public function getDescriptionAttribute(): string
    {
        return $this->information->description ?? "";
    }

    public function getImageAttribute(): string|null
    {
        return $this->information->image;
    }
    
    public function getStartAttribute(): Carbon|null
    {
        return $this->information->start;
    }
    
    public function getEndAttribute(): Carbon|null
    {
        return $this->information->end;
    }

    public function getNotesAttribute(): string|null
    {
        return empty($this->information->notes) ? null : $this->information->notes;
    }

    public function getUpgradeNameAttribute(): string
    {
        return $this->information->upgrade_name;
    }

    /**
     * @return array<string, string>
     */
    public function getAttributesAttribute(): array
    {
        return $this->information->attributes;
    }

    public function getIconAttribute(): View|string|Closure
    {
        return $this->information->icon;
    }

    public function equals(AbstractComponent $component): bool
    {
        return $this->component->getComponentType() === $component->component->getComponentType() && $this->component->get()->id === $component->component->get()->id;
    }

    public abstract function getOwnedAttribute(): bool;

    /**
     * @return AbstractComponent[]
     */
    public abstract function getAvailableUpgrades(): array;

    public abstract function purchaseForOne(): bool;

    public abstract function purchaseForAll(): bool;

    public abstract function sellForOne(): bool;

    public abstract function sellForAll(): bool;
}
