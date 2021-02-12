<?php

namespace App\Actions;

use Illuminate\Support\Facades\Redirect;
use TCG\Voyager\Actions\AbstractAction;
use App\Controller\OrderCustomerController;

class getOrderCustomerComponentsAction extends AbstractAction
{
    public function getTitle()
    {
        return 'View Components';
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
        return $this->dataType->slug === 'orders-customers';
    }

    public function getDefaultRoute()
    {
        $orderCustomerId = $this->data->id;
        return route('customerComponents', ['id'=>$orderCustomerId]);
    }
}