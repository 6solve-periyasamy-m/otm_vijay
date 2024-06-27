<?php

namespace App\View\Components\Pdf\Alternative;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Divider extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return
        <<<'blade'
            <table class="divider">
                <thead>
                    <tr>
                        <th class="text">
                            {{ $slot }}
                        </th>
                    </tr>
                </thead>
            </table>
        blade;
    }
}
