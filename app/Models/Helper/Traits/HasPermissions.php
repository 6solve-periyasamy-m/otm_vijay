<?php

namespace App\Models\Helper\Traits;

use App\Models\Helper\PermissionSet;
use Silber\Bouncer\BouncerFacade as Bouncer;

trait HasPermissions
{
    public function canCreate(): bool
    {
        return $this->can('create');
    }

    public function canRead(): bool
    {
        return $this->can('read');
    }

    public function canUpdate(): bool
    {
        return $this->can('update');
    }

    public function canDelete(): bool
    {
        return $this->can('delete');
    }

    public function can(string $action): bool
    {
        return Bouncer::can($action, app(static::class));
    }

    public function getPermissionSet(string $action): PermissionSet
    {
        return new PermissionSet(static::class, $action);
    }
}
