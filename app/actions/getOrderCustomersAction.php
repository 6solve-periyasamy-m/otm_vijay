<?php

namespace App\Actions;

use Illuminate\Support\Facades\Redirect;
use TCG\Voyager\Actions\AbstractAction;

class getOrderCustomersAction extends AbstractAction
{
    public function getTitle()
    {
        return 'View Customers';
    }

    public function getIcon()
    {
        return 'voyager-eye';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary pull-right',
        ];
    }

    public function shouldActionDisplayOnDataType() {
        //Display this action only for the Posts
        return $this->dataType->slug === 'orders';
    }

    public function getDefaultRoute()
    {
        $order_id = $this->data->id;
        return route('voyager.orders-customers.index',  ['key=order_id','filter=equals', "s={$order_id}"]);
    }
}