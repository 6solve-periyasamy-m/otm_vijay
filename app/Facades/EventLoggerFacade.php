<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class EventLoggerFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'event-logger';
    }

}
