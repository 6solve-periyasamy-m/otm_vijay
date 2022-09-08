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
    public function __construct(string $name, array $labels, array $values)
    {
        $this->name = $name;
        $this->labels = $labels;
        $this->values = $values;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.chart.donut', ['name' => $this->name, 'labels' => $this->labels, 'values' => $this->values,]);
    }
}
