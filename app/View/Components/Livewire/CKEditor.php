<?php

namespace App\View\Components\Livewire;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CKEditor extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.livewire.ckeditor');
    }
}
