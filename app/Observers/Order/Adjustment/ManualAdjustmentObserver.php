<?php

namespace App\Observers\Order\Adjustment;

use App\Models\Order\Adjustment\ManualAdjustment;
use App\Observers\UpdatesOrder;

class ManualAdjustmentObserver
{
    use UpdatesOrder;
    
    /**
     * Handle the ManualAdjustment "created" event.
     *
     * @param ManualAdjustment $manualAdjustment
     * @return void
     */
    public function created(ManualAdjustment $manualAdjustment)
    {
        $this->updateOrder($manualAdjustment->order);
    }

    /**
     * Handle the ManualAdjustment "updated" event.
     *
     * @param ManualAdjustment $manualAdjustment
     * @return void
     */
    public function updated(ManualAdjustment $manualAdjustment)
    {
        $this->updateOrder($manualAdjustment->order);
    }

    /**
     * Handle the ManualAdjustment "deleted" event.
     *
     * @param ManualAdjustment $manualAdjustment
     * @return void
     */
    public function deleted(ManualAdjustment $manualAdjustment)
    {
        $this->updateOrder($manualAdjustment->order);
    }

    /**
     * Handle the ManualAdjustment "restored" event.
     *
     * @param ManualAdjustment $manualAdjustment
     * @return void
     */
    public function restored(ManualAdjustment $manualAdjustment)
    {
        $this->updateOrder($manualAdjustment->order);
    }

    /**
     * Handle the ManualAdjustment "force deleted" event.
     *
     * @param ManualAdjustment $manualAdjustment
     * @return void
     */
    public function forceDeleted(ManualAdjustment $manualAdjustment)
    {
        $this->updateOrder($manualAdjustment->order);
    }
}
