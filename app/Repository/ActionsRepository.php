<?php

namespace App\Repository;

use App\Models\Action;

interface ActionsRepositoryInterface {
    public static function log($action, $customer_id, $order_id, $reference = '', $detail = '');
}

class ActionsRepository implements ActionsRepositoryInterface
{
    protected $model;

    public static function log($message, $customer_id, $order_id, $reference = '', $detail = '')
    {
        $action = new Action();
        $action->action = $message;
        $action->customer_id = $customer_id;
        $action->order_id = $order_id;
        $action->reference = $reference;
        $action->detail = $detail;
        $action->save();
    }
}
