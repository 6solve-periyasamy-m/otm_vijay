<?php

namespace App\View\Components\Chart;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Line extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $name, array $labels, array $values)
    {
        $this->name = $name;
        $this->labels = $labels;
        $this->values = $values;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.chart.line', ['name' => $this->name, 'labels' => $this->labels, 'values' => $this->values,]);
    }
}
