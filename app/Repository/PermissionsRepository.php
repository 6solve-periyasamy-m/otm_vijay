<?php

namespace App\Repository;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\Database\Role;
use \Silber\Bouncer\BouncerFacade as Bouncer;

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
    public static function getCurrentLevel();
}

class PermissionsRepository implements PermissionsRepositoryInterface
{
    public static function getAvailablePermissionSets() {
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
            'AccommodationInventoryTour' => [
                'name' => 'Accommodation Tour',
                'group' => 'Tour and Components',
                'order' => 2,
            ],
            'ActivityInventoryTour' => [
                'name' => 'Activity Tour',
                'group' => 'Tour and Components',
                'order' => 3,
            ],
            'FlightInventoryTour' => [
                'name' => 'Flight Tour',
                'group' => 'Tour and Components',
                'order' => 4,
            ],
            'TransportInventoryTour' => [
                'name' => 'Transport Tour',
                'group' => 'Tour and Components',
                'order' => 5,
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

    public static function getAvailablePermissionClasses() {
        return array_keys(self::getAvailablePermissionSets());
    }

    public static function getGroupedPermissions(Role $role = null) {
        $permissions = [];
        foreach (self::getAvailablePermissionSets() as $class => $values) {
            if (!isset($permissions[$values['group']])) { $permissions[$values['group']] = []; }
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

    public static function getPermissionStatus(Role $role, string $ability, string $class) {
        return $role->can($ability, '\\App\\Models\\' . $class);
    }

    public static function canCurrentUser(string $action, string $class) {
        return Bouncer::can($action, '\\App\\Models\\' . $class);
    }

    /**
     * @throws AuthorizationException
     */
    public static function grantPermission(Role $role, string $ability, string $class, $onFail = null) {
        if (!self::canCurrentUser($ability, $class)) {
            if (!isset($onFail)) {
                throw new AuthorizationException('Role does not have access to this permission');
            } else {
                $onFail();
            }
        }
        Bouncer::allow($role)->to($ability, '\\App\\Models\\' . $class);
    }

    /**
     * @throws AuthorizationException
     */
    public static function revokePermission(Role $role, string $ability, string $class, $onFail = null) {
        if (!self::canCurrentUser($ability, $class)) {
            if (!isset($onFail)) {
                throw new AuthorizationException('Role does not have access to this permission');
            } else {
                $onFail();
            }
        }
        Bouncer::disallow($role)->to($ability, '\\App\\Models\\' . $class);
    }

    public static function createRole(string $name, string $title, int $level) {
        return Bouncer::roles()->firstOrCreate([
            'name' => $name,
            'title' => $title,
            'level' => $level,
        ]);
    }

    public static function createPresetRole(string $title, int $level) {
        return self::createRole(strtolower(str_replace(' ', '-', $title)), $title, $level);
    }

    public static function getCurrentLevel() {
        if (!Auth::guard('web')->check()) return -1;
        return Auth::user()->getHighestRoleLevel();
    }
}
