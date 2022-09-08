<?php

namespace App\View\Components\Chart;

use Illuminate\View\Component;

class Donut extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $name, array $labels, array $values, array $colors = [], bool $half = false)
    {
        $this->name = $name;
        $this->labels = $labels;
        $this->values = $values;
        $missing = sizeof($labels) - sizeof($colors);
        if ($missing > 0) {
            $colors = [...$colors, random_colors($missing)];
        }
        $this->colors = $colors;
        $this->half = $half;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.chart.donut', ['name' => $this->name, 'labels' => $this->labels, 'values' => $this->values, 'colors' => $this->colors, 'half' => $this->half]);
    }
}
