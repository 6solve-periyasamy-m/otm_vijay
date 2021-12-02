<?php

namespace App\Http\Controllers;

use App\Repository\PermissionsRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Silber\Bouncer\Database\Role;

class PermissionsController extends Controller
{
    public function index()
    {
        return view('pages.roles.table', ['roles' => Role::all(),]);
    }

    public function create()
    {
        return view('pages.roles.create', ['permissions' => PermissionsRepository::getGroupedPermissions(),]);
    }

    public function store(Request $request)
    {
        $request->validate($this->getValidationRules());
        $role = PermissionsRepository::createPresetRole($request->input('title'), $request->input('level'));
        $this->processRequest($role, $request);
        return redirect()->route('roles.all');
    }

    public function edit(Role $role)
    {
        if ($role->level >= PermissionsRepository::getCurrentLevel()) {
            abort(403, 'You cannot delete a role that is higher than or equal to your permission level');
        }
        return view('pages.roles.update', ['role' => $role, 'permissions' => PermissionsRepository::getGroupedPermissions($role)]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate($this->getValidationRules());
        PermissionsRepository::updateRole($role, $request->input('title'), $request->input('level'));
        $this->processRequest($role, $request);
        return redirect()->route('roles.all');
    }

    public function destroy(Role $role)
    {
        if ($role->level >= PermissionsRepository::getCurrentLevel()) {
            abort(403, 'You cannot delete a role that is higher than or equal to your permission level');
        }
        $role->delete();
        return redirect()->route('roles.all');
    }

    private function processRequest($role, Request $request)
    {
        $available = PermissionsRepository::getAvailablePermissionClasses();
        PermissionsRepository::revokeEverything($role);
        foreach ($available as $class) {
            try {
                foreach (['create', 'read', 'update', 'delete'] as $action) {
                    if (!PermissionsRepository::canCurrentUser($action, $class)) continue;
                    if ($request->has($class . '-' . $action)) {
                        PermissionsRepository::grantPermission($role, $action, $class,  function () {});
                    } else {
                        PermissionsRepository::revokePermission($role, $action, $class, function () {});
                    }
                }
            } catch (InvalidArgumentException $e) {
                Log::error($e);
                continue;
            } catch (AuthorizationException $e) {
                abort(403, $e->getMessage());
            }

        }
    }

    private function getValidationRules()
    {
        return [
            'title' => 'required',
            'level' => [
                'required',
                'integer',
                'between:0,' . (PermissionsRepository::getCurrentLevel()-1),
            ],
        ];
    }
}
