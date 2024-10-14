<?php

namespace App\Models\Traits;

use App\View\Components\DisplayModeComponent;
use App\View\Helper\DisplayModeColor;
use App\View\Helper\DisplayModeType;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @property string $name
 * @property DisplayModeType|null $display_mode_type
 * @property DisplayModeColor|null $display_mode_color
 */
trait HasDisplayMode
{
    public function getDisplay(): View|string|Closure
    {
        return (new DisplayModeComponent($this->name, $this->display_mode_type, $this->display_mode_color))->render();
    }
}
