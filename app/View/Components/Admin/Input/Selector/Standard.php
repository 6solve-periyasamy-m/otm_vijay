<?php

namespace App\View\Components\Admin\Input\Selector;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Standard extends Component
{

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.admin.input.selector.standard');
    }
}
