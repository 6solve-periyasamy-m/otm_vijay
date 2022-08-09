<?php

namespace App\View\Components\Admin\Input\Selector;

use Illuminate\View\Component;

class Standard extends Component
{

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.input.selector.standard');
    }
}
