<?php

namespace App\Helpers\Storage;

use App\Models\Order\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class Quarter
{
    public readonly Carbon $start;
    public readonly Carbon $end;
    public readonly int $year;
    public readonly int $quarter;

    public function __construct(Carbon $start, Carbon $end, int $year, int $quarter)
    {
        $this->start = $start;
        $this->end = $end;
        $this->year = $year;
        $this->quarter = $quarter;
    }

    /**
     * Get all orders placed within the quarter
     * @return Collection<Order>
     */
    public function getPlacedOrders(): Collection
    {
        return Order::whereBetween('ordered_on', [$this->start, $this->end])->get();
    }

    /**
     * Get all orders departing within the quarter
     * @return Collection<Order>
     */
    public function getDepartingOrders(): Collection
    {
        return Order::with('tour', function ($query) {
            /** @var Builder $query */
            return $query->whereBetween('date_from', [$this->start, $this->end]);
        })->get();
    }

    public function getDepartingAfterOrders(): Collection
    {
        return Order::with('tour', function ($query) {
            /** @var Builder $query */
            return $query->whereDate('date_from', '>', $this->end);
        })->get();
    }
}