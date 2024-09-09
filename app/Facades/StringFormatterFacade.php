<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class StringFormatterFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'string-formatter';
    }

}
