<?php

namespace App\Http\Livewire\Abstract;

use App\Models\Helper\Enum\OrderStatus;
use App\View\Components\Badge\Order as OrderBadge;
use Mediconesystems\LivewireDatatables\Column;

class OrderBadgeColumn extends Column
{
    public function __construct()
    {
        parent::__construct();
        $this->callback = function ($value) {  return (new OrderBadge(OrderStatus::from($value)))->render(); };
        $this->exportCallback = function ($value) { return OrderStatus::from($value)->description(); };
    }
}
