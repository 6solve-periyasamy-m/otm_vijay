<?php

namespace App\View\Components\Admin\System\Tax;

use App\Models\System\TaxBracket;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tile extends Component
{
    public TaxBracket $bracket;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(TaxBracket $bracket)
    {
        $this->bracket = $bracket;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.system.tax.tile');
    }
}
