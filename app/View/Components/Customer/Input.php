<?php

namespace App\View\Components\Customer;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{

    public $width;
    public $disable;

    public function __construct($width = 12, $disable = false)
    {
        $this->disable = $disable;
        $this->width = $width;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.customer.input');
    }
}
