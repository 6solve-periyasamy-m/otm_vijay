<?php

namespace App\View\Components\Customer;

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
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.customer.input');
    }
}
