<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class IconFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'icon';
    }

}
