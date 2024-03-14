<?php

namespace App\Observers;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;

abstract class OrderUpdateObserver
{
    protected abstract function getOrder(Model $model): Order|null;  use UpdatesOrder;

    /**
     * Handle the Model "created" event.
     *
     * @param Model $model
     * @return void
     */
    public function created(Model $model)
    {
        $this->updateOrder($this->getOrder($model));
    }

    /**
     * Handle the Model "updated" event.
     *
     * @param Model $model
     * @return void
     */
    public function updated(Model $model)
    {
        $this->updateOrder($this->getOrder($model));
    }

    /**
     * Handle the Model "deleted" event.
     *
     * @param Model $model
     * @return void
     */
    public function deleted(Model $model)
    {
        $this->updateOrder($this->getOrder($model));
    }

    /**
     * Handle the Model "restored" event.
     *
     * @param Model $model
     * @return void
     */
    public function restored(Model $model)
    {
        $this->updateOrder($this->getOrder($model));
    }

    /**
     * Handle the Model "force deleted" event.
     *
     * @param Model $model
     * @return void
     */
    public function forceDeleted(Model $model)
    {
        $this->updateOrder($this->getOrder($model));
    }
}