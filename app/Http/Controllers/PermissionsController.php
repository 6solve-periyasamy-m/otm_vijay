<?php

namespace App\Http\Controllers;

use App\Repository\PermissionsRepository;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Silber\Bouncer\Database\Role;
use \Silber\Bouncer\BouncerFacade as Bouncer;

class PermissionsController extends Controller
{
     public function showPermissionScreen(Role $role) {
        return view('pages.users.permissions', ['role' => $role, 'permissions' => PermissionsRepository::getGroupedPermissions($role)]);
     }

     public function index() {
         return view('pages.roles.table', ['roles' => Role::all(),]);
     }

     public function create() {
         return view('pages.roles.create', ['permissions' => PermissionsRepository::getGroupedPermissions(),]);
     }

     public function store(Request $request) {
         $role = Bouncer::role()->firstOrCreate([
             'name' => strtolower(str_replace(' ', '-', $request->input('title'))),
             'title' => $request->input('title'),
             'level' => $request->input('level'),
         ]);
         $this->processRequest($role, $request);
         return redirect()->route('roles.all');
     }

     public function edit(Role $role) {
         return view('pages.roles.update', ['role'=>$role,  'permissions' => PermissionsRepository::getGroupedPermissions($role)]);
     }

     public function update(Request $request, Role $role) {
         $role->title = $request->input('title');
         $role->level = $request->input('level');
         $role->save();
         $this->processRequest($role, $request);
         return redirect()->route('roles.all');
     }

     public function destroy(Role $role) {
         return redirect()->route('roles.all');
     }

     private function processRequest($role, Request $request) {
         $available = PermissionsRepository::getAvailablePermissionClasses();
         foreach ($available as $class) {
             try {
                 foreach (['create','read','update','delete'] as $action) {
                     if (!PermissionsRepository::canCurrentUser($action, $class)) continue;
                     if ($request->has($class . '-' . $action)) {
                         PermissionsRepository::grantPermission($role, $action, $class);
                     } else {
                         PermissionsRepository::revokePermission($role, $action, $class);
                     }
                 }
             } catch (\InvalidArgumentException $e) {
                 dd($class, $e);
             }

         }
     }
}
