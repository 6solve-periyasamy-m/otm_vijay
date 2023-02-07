<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Icon extends Component
{
    private string|null $icon;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string|null $icon = null)
    {
        $this->icon = $icon;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.icon', ['icon' => $this->icon,]);
    }

    public function __toString()
    {
        return $this->render();
    }
}
