<?php

namespace App\Transforms;

use Illuminate\Database\Eloquent\Collection;

interface PermissionTransformsInterface
{
    public static function getRolesForDropdown(Collection $roles);
}

class PermissionTransforms implements PermissionTransformsInterface
{

    public static function getRolesForDropdown(Collection $roles): array
    {
        $data = [];
        foreach ($roles as $role) {
            $data[$role->name] = $role->title . ' - Level ' . $role->level;
        }
        return $data;
    }
}
