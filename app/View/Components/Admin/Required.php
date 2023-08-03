<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class Required extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.required');
    }
}
