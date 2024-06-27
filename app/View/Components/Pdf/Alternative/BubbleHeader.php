<?php

namespace App\View\Components\Pdf\Alternative;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BubbleHeader extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return
        <<<'blade'
            <table class="date-header">
                <tr>
                    <td colspan="2" class="text-left">
                        <div class="date-content">
                            {{ $slot }}
                        </div>
                    </td>
                </tr>
            </table>
        blade;
}
}
