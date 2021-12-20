<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Silber\Bouncer\Database\Role;

interface PermissionsRepositoryInterface
{
    public static function getAvailablePermissionSets();

    public static function getAvailablePermissionClasses();

    public static function getGroupedPermissions(Role $role = null);

    public static function getPermissionStatus(Role $role, string $ability, string $class);

    public static function canCurrentUser(string $action, string $class);

    public static function grantPermission(Role $role, string $ability, string $class);

    public static function revokePermission(Role $role, string $ability, string $class);

    public static function createRole(string $name, string $title, int $level);

    public static function createPresetRole(string $title, int $level);

    public static function updateRole(Role $role, string $title, int $level);

    public static function getCurrentLevel();

    public static function getAvailableRoles();

    public static function getDefaultRole();

    public static function assignRole(User $user, string $newRole);

    public static function getRoleFromName(string $role);
}

class PermissionsRepository implements PermissionsRepositoryInterface
{
    public static function getAvailablePermissionClasses()
    {
        return array_keys(self::getAvailablePermissionSets());
    }

    public static function getAvailablePermissionSets()
    {
        return [
            // Tours
            'Tour' => [
                'name' => 'Tour',
                'group' => 'Tour and Components',
                'order' => 0,
            ],
            'Event' => [
                'name' => 'Event',
                'group' => 'Tour and Components',
                'order' => 1,
            ],
            'TourCategory' => [
                'name' => 'Category',
                'group' => 'Tour and Components',
                'order' => 2,
            ],
            'AccommodationInventoryTour' => [
                'name' => 'Accommodation Tour',
                'group' => 'Tour and Components',
                'order' => 3,
            ],
            'ActivityInventoryTour' => [
                'name' => 'Activity Tour',
                'group' => 'Tour and Components',
                'order' => 4,
            ],
            'FlightInventoryTour' => [
                'name' => 'Flight Tour',
                'group' => 'Tour and Components',
                'order' => 5,
            ],
            'TransportInventoryTour' => [
                'name' => 'Transport Tour',
                'group' => 'Tour and Components',
                'order' => 6,
            ],
            'Merchandise' => [
                'name' => 'Merchandise',
                'group' => 'Tour and Components',
                'order' => 7,
            ],
            // Accommodations
            'Accommodation' => [
                'name' => 'Accommodation',
                'group' => 'Accommodation',
                'order' => 0,
            ],
            'AccommodationInventory' => [
                'name' => 'Inventory',
                'group' => 'Accommodation',
                'order' => 1,
            ],
            'RoomType' => [
                'name' => 'Room Types',
                'group' => 'Accommodation',
                'order' => 2,
            ],
            'BoardType' => [
                'name' => 'Board Types',
                'group' => 'Accommodation',
                'order' => 3,
            ],
            // Activities
            'Activity' => [
                'name' => 'Activity',
                'group' => 'Activity',
                'order' => 0,
            ],
            'ActivityType' => [
                'name' => 'Activity Type',
                'group' => 'Activity',
                'order' => 2,
            ],
            'ActivityInventory' => [
                'name' => 'Inventory',
                'group' => 'Activity',
                'order' => 1,
            ],
            'TicketType' => [
                'name' => 'Ticket Type',
                'group' => 'Activity',
                'order' => 3,
            ],
            // Flights
            'Flight' => [
                'name' => 'Flight',
                'group' => 'Flight',
                'order' => 0,
            ],
            'FlightInventory' => [
                'name' => 'Inventory',
                'group' => 'Flight',
                'order' => 1,
            ],
            'Airport' => [
                'name' => 'Airport',
                'group' => 'Flight',
                'order' => 2,
            ],
            'Airline' => [
                'name' => 'Airline',
                'group' => 'Flight',
                'order' => 3,
            ],
            // Transports
            'Transport' => [
                'name' => 'Transport',
                'group' => 'Transport',
                'order' => 0,
            ],
            'TransportInventory' => [
                'name' => 'Inventory',
                'group' => 'Transport',
                'order' => 1,
            ],
            'Operator' => [
                'name' => 'Operator',
                'group' => 'Transport',
                'order' => 2,
            ],
            'TransportType' => [
                'name' => 'Transport Type',
                'group' => 'Transport',
                'order' => 3,
            ],
            // Orders
            'Order' => [
                'name' => 'Order',
                'group' => 'Orders',
                'order' => 0,
            ],
            'Payment' => [
                'name' => 'Payment',
                'group' => 'Orders',
                'order' => 1,
            ],
            'ManualAdjustment' => [
                'name' => 'Manual Adjustment',
                'group' => 'Orders',
                'order' => 2,
            ],
            // Customers
            'Customer' => [
                'name' => 'Customer',
                'group' => 'Orders',
                'order' => 0,
            ],
            'OrderCustomer' => [
                'name' => 'Order Customer',
                'group' => 'Orders',
                'order' => 1,
            ],
            'OrderCustomerAdjustment' => [
                'name' => 'Customer Adjustment',
                'group' => 'Orders',
                'order' => 2,
            ],
            // System
            'Setting' => [
                'name' => 'Setting',
                'group' => 'System',
                'order' => 0,
            ],
            'User' => [
                'name' => 'User',
                'group' => 'System',
                'order' => 1,
            ],

        ];
    }

    public static function getGroupedPermissions(Role $role = null)
    {
        $permissions = [];
        foreach (self::getAvailablePermissionSets() as $class => $values) {
            if (!isset($permissions[$values['group']])) {
                $permissions[$values['group']] = [];
            }
            $permissions[$values['group']][$class] = [
                'name' => $values['name'],
                'create' => isset($role) && self::getPermissionStatus($role, 'create', $class),
                'read' => isset($role) && self::getPermissionStatus($role, 'read', $class),
                'update' => isset($role) && self::getPermissionStatus($role, 'update', $class),
                'delete' => isset($role) && self::getPermissionStatus($role, 'delete', $class),
            ];
        }
        return $permissions;
    }

    public static function getPermissionStatus(Role $role, string $ability, string $class)
    {
        return $role->can($ability, '\\App\\Models\\' . $class);
    }

    /**
     * @throws AuthorizationException
     */
    public static function grantPermission(Role $role, string $ability, string $class, $onFail = null)
    {
        if (!self::canCurrentUser($ability, $class)) {
            if (!isset($onFail)) {
                throw new AuthorizationException('Role does not have access to this permission');
            } else {
                $onFail();
            }
        }
        Bouncer::allow($role)->to($ability, '\\App\\Models\\' . $class);
    }

    public static function canCurrentUser(string $action, string $class)
    {
        return Bouncer::can($action, '\\App\\Models\\' . $class);
    }

    /**
     * @throws AuthorizationException
     */
    public static function revokePermission(Role $role, string $ability, string $class, $onFail = null)
    {
        if (!self::canCurrentUser($ability, $class)) {
            if (!isset($onFail)) {
                throw new AuthorizationException('Role does not have access to this permission');
            } else {
                $onFail();
            }
        }
        Bouncer::disallow($role)->to($ability, '\\App\\Models\\' . $class);
    }

    public static function createPresetRole(string $title, int $level)
    {
        return self::createRole(strtolower(str_replace(' ', '-', $title)), $title, $level);
    }

    public static function createRole(string $name, string $title, int $level)
    {
        return Bouncer::roles()->firstOrCreate([
            'name' => $name,
            'title' => $title,
            'level' => $level,
        ]);
    }

    public static function updateRole(Role $role, string $title, int $level)
    {
        $role->name = strtolower(str_replace(' ', '-', $title));
        $role->title = $title;
        $role->level = $level;
        $role->save();
        return $role;
    }

    public static function getAvailableRoles()
    {
        return Role::where('level', '<', self::getCurrentLevel())->get();
    }

    public static function getCurrentLevel()
    {
        if (!Auth::guard('web')->check()) return -1;
        return Auth::user()->getHighestRoleLevel();
    }

    public static function getDefaultRole()
    {
        return Role::all()->sortBy('level', SORT_ASC)->first();
    }

    /**
     * @throws AuthorizationException
     */
    public static function assignRole(User $user, string $newRole)
    {
        $role = self::getRoleFromName($newRole);
        if (isset($role)) {
            if ($role->level >= self::getCurrentLevel()) {
                throw new AuthorizationException('You cannot assign a role higher than your own');
            }

            foreach ($user->roles as $iRole) {
                $user->retract($iRole);
            }

            $user->assign($newRole);
            return $user;
        }
        return null;
    }

    public static function getRoleFromName(string $role)
    {
        return Role::where('name', '=', $role)->first();
    }

    public static function revokeEverything(Role $role) {
        Bouncer::disallow($role)->everything();
    }
}
