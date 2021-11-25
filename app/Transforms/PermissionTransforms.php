<?php

namespace App\Transforms;

use App\Models\HatSize;
use App\Models\TShirtSize;
use Illuminate\Database\Eloquent\Collection;

interface PermissionTransformsInterface
{
    public static function getRolesForDropdown(Collection $roles);
}

class PermissionTransforms implements PermissionTransformsInterface
{

    public static function getRolesForDropdown(Collection $roles)
    {
        $data = [];
        foreach ($roles as $role) {
            $data[$role->name] = $role->title . ' - Level ' . $role->level;
        }
        return $data;
    }
}
