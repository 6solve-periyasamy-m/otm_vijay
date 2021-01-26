<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;
use App\Controllers\TourController;

class getTourComponentListAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Component Price/Types';
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
        return $this->dataType->slug === 'tours';
    }

    public function getDefaultRoute()
    {
        $tourId = $this->data->id;
        return route('tourComponents', ['id' => $tourId]);
    }
}