<?php

namespace App\Report;

use App\Models\Order\Order;

class OrderReport extends BespokeReport
{

    /**
     * @inheritDoc
     */
    public function getQuery()
    {
        return Order::query();
    }

    /**
     * @return ColumnDefinition[]
     */
    protected function allColumns(): array
    {

    }
}