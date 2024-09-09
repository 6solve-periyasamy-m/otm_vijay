<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Icon extends Component
{
    private string|null $icon;
    private string $base;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string|null $icon = null, string $base = 'fas')
    {
        $this->icon = $icon;
        $this->base = $base;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.icon', ['icon' => $this->icon, 'base' => $this->base,]);
    }

    public function __toString()
    {
        return $this->render();
    }
}
