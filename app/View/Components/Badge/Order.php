<?php

namespace App\View\Components\Badge;

use App\Models\Helper\Enum\OrderStatus;
use Illuminate\View\Component;

class Order extends Component
{
    private OrderStatus $status;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(OrderStatus $status)
    {
        $this->status = $status;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.badge', ['color' => $this->status->color(), 'message' => $this->status->description(),]);
    }
}
