<?php

namespace App\Events\Parent;

use App\Models\Order\Adjustment\ManualAdjustment;

abstract class OrderAdjustmentEvent extends OrderEvent
{
    /**
     * @var ManualAdjustment
     */
    public $adjustment;

    /**
     * @param ManualAdjustment $adjustment
     * @param bool $shouldInvoice
     */
    public function __construct(ManualAdjustment $adjustment, bool $shouldInvoice = true) {
        parent::__construct($adjustment->order, $shouldInvoice);
        $this->adjustment = $adjustment;
    }
}
