<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GatewayFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'gateway';
    }

}
