<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repository\Authentication\PermissionsRepository;
use App\Repository\Authentication\UserRepository;
use App\Transforms\PermissionTransforms;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Log;
use Storage;
use Throwable;

class UserController extends Controller
{

    public function index()
    {
        return view('pages.users.table', ['users' => User::withTrashed()->get(),]);
    }

    public function create()
    {
        if (UserRepository::getRemainingUserCount() <= 0) {
            return back()->withErrors(['msg' => 'You have reached your user limit. Please contact OTM to increase the user limit']);
        }
        return view('pages.users.create', ['roles' => PermissionTransforms::getRolesForDropdown(PermissionsRepository::getAvailableRoles()),
            'current' => PermissionsRepository::getDefaultRole()->name,]);
    }

    public function store(Request $request)
    {
        if (UserRepository::getRemainingUserCount() <= 0) {
            return back()->withErrors(['msg' => 'You have reached your user limit. Please contact your account manager to increase the user limit']);
        }
        $request->validate(User::getCreateValidationRules());
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        $this->updateAvatar($request, $user);
        try { event(new Registered($user)); } catch (Exception $e) { Log::error($e); }
        $user->assign($request->input('role') ?? 'user');
        return redirect()->route('users.all');
    }

    public function view(User $user)
    {
        return view('pages.users.view', ['user' => $user,]);
    }

    public function edit(User $user)
    {
        if (!$this->verifyUser($user, true)) abort(403);
        return view('pages.users.update', ['user' => $user,
            'roles' => PermissionTransforms::getRolesForDropdown(PermissionsRepository::getAvailableRoles()),
            'current' => $user->getCurrentRole()->name,]);
    }

    private function verifyUser($user, $allowSelfEdit): bool
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
        return $allow;
    }

    public function update(Request $request, User $user)
    {
        if (!$this->verifyUser($user, true)) abort(403);
        if ($user->email !== $request->input('email')) {
            $user->email_verified_at = null;
        }
        $request->validate($user->getUpdateValidationRules());
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);
        if ($request->input('new_password') != null) {
            $user->update([
                'password' => Hash::make($request->input('new_password')),
            ]);
        }
        if (Auth::user()->id !== $user->id) {
            PermissionsRepository::assignRole($user, $request->input('role'));
        }
        $this->updateAvatar($request, $user);
        try { event(new Registered($user)); } catch (Exception $e) { Log::error($e); }
        $user->save();
        return redirect()->route('users.all');
    }

    public function destroy(User $user)
    {
        if (!$this->verifyUser($user, false)) abort(403);
        $user->delete();
        return redirect()->route('users.all');
    }

    public function restore($user)
    {
        if (UserRepository::getRemainingUserCount() <= 0) abort(403);
        $user = User::withTrashed()->findOrFail($user);
        $user->restore();
        return redirect()->route('users.all');
    }

    /**
     * @param Request $request
     * @param User $user
     * @return void
     */
    private function updateAvatar(Request $request, User $user): void
    {
        if ($request->has('avatar') && $request->file('avatar') != null) {
            if (!empty($user->avatar)) {
                try {
                    Storage::delete($user->avatar);
                } catch (Throwable $e) {
                    Log::error($e);
                }
            }
            $user->avatar = $request->file('avatar')->storePublicly('uploads/images/users');
        }
    }
}
