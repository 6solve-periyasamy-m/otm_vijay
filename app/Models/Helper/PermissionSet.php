<?php

namespace App\Models\Helper;

use Silber\Bouncer\BouncerFacade as Bouncer;

class PermissionSet
{
    public function __construct(public string $class, public string $action) {}

    public function can(): bool
    {
        return Bouncer::can($this->action, app($this->class));
    }
}
