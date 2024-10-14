<?php

namespace App\View\Components;

use App\View\Helper\DisplayModeColor;
use App\View\Helper\DisplayModeType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DisplayModeComponent extends Component
{
    private string $content;
    private ?DisplayModeType $type;
    private ?DisplayModeColor $color;

    /**
     * Create a new component instance.
     */
    public function __construct(string $content, DisplayModeType|int|string|null $type, DisplayModeColor|string|null $color)
    {
        $this->content = $content;
        $this->type = DisplayModeType::get($type);
        $this->color = DisplayModeColor::get($color);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $data = ['content' => $this->content, 'color' => $this->color];
        return match($this->type) {
            DisplayModeType::BADGE => view('components.display-mode.badge', $data),
            default => view('components.display-mode.text', $data),
        };
    }
}
