<?php

namespace App\View\Components\Admin\Section;

use Illuminate\View\Component;

class Header extends Component
{

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.section.header');
    }
}
