<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repository\PermissionsRepository;
use App\Transforms\PermissionTransforms;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        return view('pages.users.table', ['users' => User::all(),]);
    }

    public function create()
    {
        return view('pages.users.create', ['roles' => PermissionTransforms::getRolesForDropdown(PermissionsRepository::getAvailableRoles()),
            'current' => PermissionsRepository::getDefaultRole()->name,]);
    }

    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        event(new Registered($user));
        $user->assign($request->input('role') ?? 'user');
        return redirect()->route('users.all');
    }

    public function view(User $user)
    {
        return view('pages.users.view', ['user' => $user,]);
    }

    public function edit(User $user)
    {
        $this->verifyUser($user, true);
        return view('pages.users.update', ['user' => $user,
            'roles' => PermissionTransforms::getRolesForDropdown(PermissionsRepository::getAvailableRoles()),
            'current' => $user->getCurrentRole()->name,]);
    }

    private function verifyUser($user, $allowSelfEdit)
    {
        $allow = (Auth::guard('web')->check());
        if ($allowSelfEdit) {
            $allow = $allow &&
                ((Auth::user()->getHighestRoleLevel() > $user->getHighestRoleLevel()
                    || Auth::user()->id == $user->id));
        } else {
            $allow = $allow &&
                Auth::user()->getHighestRoleLevel() > $user->getHighestRoleLevel();
        }
        if ($allow) return true;
        return abort(403, 'You are not authorized to perform this action');
    }

    public function update(Request $request, User $user)
    {
        $this->verifyUser($user, true);
        if ($user->email !== $request->input('email')) {
            $user->email_verified_at = null;
        }
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        if (Auth::user()->id !== $user->id) {
            PermissionsRepository::assignRole($user, $request->input('role'));
        }
        event(new Registered($user));
        $user->save();
        return redirect()->route('users.all');
    }

    public function destroy(User $user)
    {
        $this->verifyUser($user, false);
        $user->delete();
        return redirect()->route('users.all');
    }
}
