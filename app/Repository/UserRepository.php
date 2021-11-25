<?php

namespace App\Repository;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Silber\Bouncer\Database\Role;
use \Silber\Bouncer\BouncerFacade as Bouncer;

interface UserRepositoryInterface
{
    public static function getLatestToken(User $user) : ApiToken;
    public static function getUserFromToken(string $token) : User;
    public static function generateUserToken(User $user) : ApiToken;
    public static function purgeUserTokens(User $user, int $limit = ApiToken::DEFAULT_LIMIT) : void;
    public static function invalidateAllUserTokens(User $user) : void;
}

class UserRepository implements UserRepositoryInterface
{

    public static function getLatestToken(User $user) : ApiToken
    {
        $token = $user->tokens()->latest()->first();
        if (!(isset($token) && !$token->hasExpired())) $token = $user->generateToken();
        return $token;
    }

    public static function getUserFromToken(string $token) : User
    {
        $apiToken = ApiToken::findOrFail($token);
        return $apiToken->user;
    }

    public static function generateUserToken(User $user, int $expiresIn = ApiToken::DEFAULT_EXPIRY) : ApiToken
    {
        // This will attempt to create an API key, and re-attempt if a collision occurs. Should be rare, but may bite us in future
        while (true) {
            try {
                $apiToken = ApiToken::make([
                    'token' => Str::random(32),
                    'expiry' => now()->addMinutes($expiresIn),
                ]);
                $user->tokens()->save($apiToken);
                return $apiToken;
            } catch (QueryException $ignored) { continue; }
        }
    }

    public static function purgeUserTokens(User $user, int $limit = ApiToken::DEFAULT_LIMIT) : void
    {
        foreach ($user->tokens as $token) {
            if (now()->addHours($limit*-1)->isAfter($token->expiry)) {
                $token->forceDelete();
            }
        }
    }

    public static function invalidateAllUserTokens(User $user) : void
    {
        foreach ($user->tokens as $token) {
            if (!$token->hasExpired()) {
                $token->invalidate();
            }
        }
    }

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
                'name' => 'Flight',
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
                'group' => 'Customers',
                'order' => 0,
            ],
            'OrderCustomer' => [
                'name' => 'Order Customer',
                'group' => 'Customers',
                'order' => 1,
            ],
            'OrderCustomerAdjustment' => [
                'name' => 'Customer Adjustment',
                'group' => 'Customers',
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
        return $role->can($ability, app('\\App\\Models\\' . $class));
    }


}
