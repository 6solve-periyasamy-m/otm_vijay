<?php

namespace App\Repository\Authentication;

use App\Models\User;
use Bouncer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\Database\Role;

class PermissionsRepository
{
    public static function getAvailablePermissionClasses(): array
    {
        return array_keys(self::getAvailablePermissionSets());
    }

    public static function getAvailablePermissionSets(): array
    {
        return [
            // Tours
            'Tour\Tour' => [
                'name' => 'Tour',
                'group' => 'Tour and Components',
                'costing' => true,
                'order' => 0,
            ],
            'Tour\Event' => [
                'name' => 'Event',
                'group' => 'Tour and Components',
                'order' => 1,
            ],
            'Tour\TourCategory' => [
                'name' => 'Category',
                'group' => 'Tour and Components',
                'order' => 2,
            ],
            'Accommodation\AccommodationInventoryTour' => [
                'name' => 'Accommodation Tour',
                'group' => 'Tour and Components',
                'order' => 3,
            ],
            'Activity\ActivityInventoryTour' => [
                'name' => 'Activity Tour',
                'group' => 'Tour and Components',
                'order' => 4,
            ],
            'Flight\FlightInventoryTour' => [
                'name' => 'Flight Tour',
                'group' => 'Tour and Components',
                'order' => 5,
            ],
            'Transport\TransportInventoryTour' => [
                'name' => 'Transport Tour',
                'group' => 'Tour and Components',
                'order' => 6,
            ],
            'Merchandise\Merchandise' => [
                'name' => 'Merchandise',
                'group' => 'Tour and Components',
                'order' => 7,
            ],
            'Quote\Quote' => [
                'name' => 'Quote',
                'group' => 'Tour and Components',
                'costing' => true,
                'order' => 8,
            ],
            // Accommodations
            'Accommodation\Accommodation' => [
                'name' => 'Accommodation',
                'group' => 'Accommodation',
                'order' => 0,
            ],
            'Accommodation\AccommodationInventory' => [
                'name' => 'Inventory',
                'group' => 'Accommodation',
                'order' => 1,
            ],
            'Accommodation\RoomType' => [
                'name' => 'Room Types',
                'group' => 'Accommodation',
                'order' => 2,
            ],
            'Accommodation\BoardType' => [
                'name' => 'Board Types',
                'group' => 'Accommodation',
                'order' => 3,
            ],
            // Activities
            'Activity\Activity' => [
                'name' => 'Activity',
                'group' => 'Activity',
                'order' => 0,
            ],
            'Activity\ActivityType' => [
                'name' => 'Activity Type',
                'group' => 'Activity',
                'order' => 2,
            ],
            'Activity\ActivityInventory' => [
                'name' => 'Inventory',
                'group' => 'Activity',
                'order' => 1,
            ],
            'Activity\TicketType' => [
                'name' => 'Ticket Type',
                'group' => 'Activity',
                'order' => 3,
            ],
            // Flights
            'Flight\Flight' => [
                'name' => 'Flight',
                'group' => 'Flight',
                'order' => 0,
            ],
            'Flight\FlightInventory' => [
                'name' => 'Inventory',
                'group' => 'Flight',
                'order' => 1,
            ],
            'Flight\Airport' => [
                'name' => 'Airport',
                'group' => 'Flight',
                'order' => 2,
            ],
            'Flight\Airline' => [
                'name' => 'Airline',
                'group' => 'Flight',
                'order' => 3,
            ],
            // Transports
            'Transport\Transport' => [
                'name' => 'Transport',
                'group' => 'Transport',
                'order' => 0,
            ],
            'Transport\TransportInventory' => [
                'name' => 'Inventory',
                'group' => 'Transport',
                'order' => 1,
            ],
            'Transport\Operator' => [
                'name' => 'Operator',
                'group' => 'Transport',
                'order' => 2,
            ],
            'Transport\TransportType' => [
                'name' => 'Transport Type',
                'group' => 'Transport',
                'order' => 3,
            ],
            'Location\LocationType' => [
                'name' => 'Location Type',
                'group' => 'Transport',
                'order' => 4,
            ],
            // Orders
            'Order\Order' => [
                'name' => 'Order',
                'group' => 'Orders',
                'order' => 0,
            ],
            'Order\Payment\Payment' => [
                'name' => 'Payment',
                'group' => 'Orders',
                'order' => 1,
            ],
            'Order\Adjustment\ManualAdjustment' => [
                'name' => 'Manual Adjustment',
                'group' => 'Orders',
                'order' => 2,
            ],
            // Customers
            'Customer\Customer' => [
                'name' => 'Customer',
                'group' => 'Orders',
                'order' => 0,
            ],
            'Order\OrderCustomer' => [
                'name' => 'Order Customer',
                'group' => 'Orders',
                'order' => 1,
            ],
            'Order\Adjustment\OrderCustomerAdjustment' => [
                'name' => 'Customer Adjustment',
                'group' => 'Orders',
                'order' => 2,
            ],
            // System
            'System\Report' => [
                'name' => 'Report',
                'group' => 'System',
                'order' => 0,
            ],
            'System\Setting' => [
                'name' => 'Setting',
                'group' => 'System',
                'order' => 1,
            ],
            'User' => [
                'name' => 'User',
                'group' => 'System',
                'order' => 2,
            ],

        ];
    }

    public static function getGroupedPermissions(Role $role = null): array
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
            if (array_key_exists('costing', $values)) {
                $permissions[$values['group']][$class]['costing'] = isset($role) && self::getPermissionStatus($role, 'costing', $class);
            }
        }
        return $permissions;
    }

    public static function getPermissionStatus(Role $role, string $ability, string $class): bool
    {
        return $role->can($ability, '\\App\\Models\\' . $class);
    }

    /**
     * @throws AuthorizationException
     */
    public static function grantPermission(Role $role, string $ability, string $class, $onFail = null): void
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

    public static function canCurrentUser(string $action, string $class): bool
    {
        $class = str_replace('App\\Models\\', '', $class);
        return Bouncer::can($action, '\\App\\Models\\' . $class);
    }

    /**
     * @throws AuthorizationException
     */
    public static function revokePermission(Role $role, string $ability, string $class, $onFail = null): void
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

    public static function createPresetRole(string $title, int $level): Role
    {
        return self::createRole(strtolower(str_replace(' ', '-', $title)), $title, $level);
    }

    public static function createRole(string $name, string $title, int $level): Role
    {
        return Bouncer::role()->firstOrCreate([
            'name' => $name,
            'title' => $title,
            'level' => $level,
        ]);
    }

    public static function updateRole(Role $role, string $title, int $level): Role
    {
        $role->name = strtolower(str_replace(' ', '-', $title));
        $role->title = $title;
        $role->level = $level;
        $role->save();
        return $role;
    }

    public static function getAvailableRoles(): Collection
    {
        return Role::where('level', '<', self::getCurrentLevel())->get();
    }

    public static function getCurrentLevel(): int
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
    public static function assignRole(User $user, string $newRole): ?User
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

    public static function getRoleFromName(string $role): Role
    {
        return Role::where('name', '=', $role)->first();
    }

    public static function revokeEverything(Role $role): void
    {
        Bouncer::disallow($role)->everything();
    }
}
