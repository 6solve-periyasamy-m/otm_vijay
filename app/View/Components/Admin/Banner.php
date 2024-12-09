<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Banner extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $content,
        public string $color = 'primary',
    )
    {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.banner', ['content' => $this->content, 'color' => $this->color]);
    }

    public static function getTemplate(): self
    {
        return new self('${content}', '${color}');
    }
}
