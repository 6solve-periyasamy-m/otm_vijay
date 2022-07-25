<?php

namespace App\View\Components\Admin\Input;

use Illuminate\View\Component;

class Checkbox extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.input.checkbox');
    }
}
