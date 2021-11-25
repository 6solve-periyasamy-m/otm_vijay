<?php

namespace App\Http\Controllers;

use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Silber\Bouncer\Database\Role;

class PermissionsController extends Controller
{
     public function showPermissionScreen(Role $role) {
        return view('pages.users.permissions', ['role' => $role, 'permissions' => UserRepository::getGroupedPermissions($role)]);
     }

     public function index() {
         return view('pages.roles.table', ['roles' => Role::all(),]);
     }

     public function view(Role $role) {
         return redirect()->route('roles.all');
     }

     public function create() {
         return view('pages.roles.create', ['permissions' => UserRepository::getGroupedPermissions(),]);
     }

     public function store(Request $request) {
         return redirect()->route('roles.all');
     }

     public function edit(Role $role) {
         return view('pages.roles.update', ['role'=>$role,  'permissions' => UserRepository::getGroupedPermissions($role)]);
     }

     public function update(Request $request, Role $role) {
         return redirect()->route('roles.all');
     }

     public function destroy(Role $role) {
         return redirect()->route('roles.all');
     }
}
