<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repository\PermissionsRepository;
use App\Repository\UserRepository;
use App\Transforms\PermissionTransforms;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Log;

class UserController extends Controller
{

    public function index()
    {
        return view('pages.users.table', ['users' => User::all(),]);
    }

    public function create()
    {
        if (UserRepository::getRemainingUserCount() <= 0) return back()->withErrors(['msg' => 'You have reached your user limit. Please contact OTM to increase the user limit']);
        return view('pages.users.create', ['roles' => PermissionTransforms::getRolesForDropdown(PermissionsRepository::getAvailableRoles()),
            'current' => PermissionsRepository::getDefaultRole()->name,]);
    }

    public function store(Request $request)
    {
        if (UserRepository::getRemainingUserCount() <= 0) return back()->withErrors(['msg' => 'You have reached your user limit. Please contact your account manager to increase the user limit']);
        $request->validate(User::getValidationRules());
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        try {
            event(new Registered($user));
        } catch (\Exception $e) { Log::error($e); }

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
        $request->validate(User::getValidationRules());
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        if (Auth::user()->id !== $user->id) {
            PermissionsRepository::assignRole($user, $request->input('role'));
        }
        try {
            event(new Registered($user));
        } catch (\Exception $e) { Log::error($e); }
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
