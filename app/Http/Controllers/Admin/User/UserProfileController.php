<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Requests\Admin\User\ChangePasswordRequest;
use App\Http\Requests\Admin\User\UpdateAvatarRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Models\User;
use App\Repository\Authentication\PermissionsRepository;
use Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Log;
use Storage;
use Throwable;

class UserProfileController
{
    public function profile(User|null $user = null)
    {
        return view('pages.admin.user.profile', ['user' => $user ?? auth()->user(),]);
    }

    public function avatar(UpdateAvatarRequest $request, User|null $user = null)
    {
        $user = $this->validateUser($user);
        if ($request->has('avatar') && $request->file('avatar') != null) {
            if (!empty($user->avatar)) {
                try {
                    Storage::delete($user->avatar);
                } catch (Throwable $e) {
                    Log::error($e);
                }
            }
            $user->avatar = $request->file('avatar')->storePublicly('uploads/images/users');
            $user->save();
        }
        return redirect()->route('users.view', ['user' => $user]);
    }

    public function update(UpdateUserRequest $request, User|null $user = null)
    {
        $current = auth()->user();
        $user = $this->validateUser($user);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'telephone' => $request->telephone,
        ]);
        $user->save();
        if ($current->id != $user->id && $current->getHighestRoleLevel() > $user->getHighestRoleLevel()) {
            try {
                PermissionsRepository::assignRole($user, $request->role);
            } catch (AuthorizationException $e) {
                return back()->withErrors(['msg' => $e->getMessage()]);
            }
        }
        return redirect()->route('users.view', ['user' => $user]);
    }

    public function password(ChangePasswordRequest $request, User|null $user = null)
    {
        $user = $this->validateUser($user);
        if ($user->id !== auth()->user()->id) {
            return back()->withErrors(['msg' => 'You cannot change another users password']);
        }
        if (Hash::check($request->current_password, $user->password)) {
            $user->update(['password' => Hash::make($request->new_password)]);
            $user->save();
            return redirect()->route('users.view', ['user' => $user]);
        } else {
            return back()->withErrors(['msg' => 'The current password is incorrect']);
        }
    }

    private function validateUser(User|null $user): User|null
    {
        $currentUser = auth()->user();
        $user = $user ?? $currentUser;
        if ($currentUser->id === $user->id || $currentUser->getHighestRoleLevel() > $user->getHighestRoleLevel()) {
            return $user;
        }
        abort(403, 'You cannot update a user of the same or higher level than you');
    }
}