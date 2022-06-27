<?php

namespace App\View\Components\Customer\Input;

use Illuminate\View\Component;

class TextArea extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.customer.input.text-area');
    }
}
