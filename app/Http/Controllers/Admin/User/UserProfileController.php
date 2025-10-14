<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Requests\Admin\Authentication\OneTimeCodeRequest;
use App\Http\Requests\Admin\User\ChangePasswordRequest;
use App\Http\Requests\Admin\User\Enable2faRequest;
use App\Http\Requests\Admin\User\UpdateAvatarRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Models\Helper\Enum\ModelEventType;
use App\Models\User;
use App\Repository\Authentication\PermissionsRepository;
use EventLogger;
use Exception;
use Google2FA;
use Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Log;
use Storage;
use Throwable;

class UserProfileController
{
    public function profile(User|null $user = null)
    {
        $user = $user ?? auth()->user();
        if (!($user->id === auth()->user()->id || auth()->user()->can("User,read"))) {
            abort(403);
        }
        return view('pages.admin.user.profile', ['user' => $user,]);
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
        EventLogger::simple($user, ModelEventType::UPDATED);
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
        EventLogger::simple($user, ModelEventType::UPDATED);
        return redirect()->route('users.profile', ['user' => $user]);
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
            EventLogger::simple($user, ModelEventType::PASSWORD_CHANGED);
            return redirect()->route('users.view', ['user' => $user]);
        } else {
            return back()->withErrors(['msg' => 'The current password is incorrect']);
        }
    }

    public function sendReset(User|null $user = null)
    {
        $user = $this->validateUser($user);
        if ($user !== null) {
            $success = $user->repository->requestReset();
            if (!$success) {
                return back()->withErrors(['msg' => 'Failed to send password reset request, please try again later.']);
            }
            return back()->with(['success' => 'Successfully sent password reset request']);
        } else {
            return back()->withErrors(['msg' => 'You do not have permission to perform this action.']);
        }
    }

    public function enable2fa(Enable2faRequest $request, User|null $user = null)
    {
        $user = $user ?? auth()->user();
        if ($user->id !== auth()->user()->id) { abort(403, 'Cannot edit security features of accounts other than your own'); }
        try {
            $valid = Google2FA::verify($request->otp_code, $request->otp_secret);
        } catch (Exception $e) {
            Log::error($e);
            $valid = false;
        }
        if ($valid) {
            $user->update(['otp_secret' => $request->otp_secret,]);
            $user->save();
            EventLogger::simple($user, ModelEventType::OTP_ENABLED);
            return redirect()->route('users.view', ['user' => $user]);
        } else {
            return back()->withErrors(['msg' => 'Could not confirm OTP, please try again.']);
        }
    }

    public function disable2fa(OneTimeCodeRequest $request, User|null $user = null)
    {
        $user = $user ?? auth()->user();
        if (flag('2fa.enforce', false) === true) {
            return back()->withErrors(['msg' => 'Two-factor authentication is enforced on the system']);
        }
        if ($user->id !== auth()->user()->id) { abort(403, 'Cannot edit security features of accounts other than your own'); }
        $valid = $user->verifyOneTimeCode($request->otp_code);
        if ($valid) {
            $user->update(['otp_secret' => null,]);
            $user->save();
            EventLogger::simple($user, ModelEventType::OTP_DISABLED);
            return redirect()->route('users.view', ['user' => $user]);
        } else {
            return back()->withErrors(['msg' => 'Could not confirm OTP, please try again.']);
        }
    }

    public function forceDisable2fa(Request $request, User $user)
    {
        if (!is_otm() || $user->isOtm()) abort(403);
        $user->update(['otp_secret' => null]);
        $user->save();
        EventLogger::simple($user, ModelEventType::OTP_DISABLED);
        return redirect()->route('users.view', ['user' => $user]);
    }

    private function validateUser(User|null $user, string $action = 'update'): User|null
    {
        $currentUser = auth()->user();
        $user = $user ?? $currentUser;
        if ($currentUser->id === $user->id ||
            ($currentUser->can("User,$action") && $currentUser->getHighestRoleLevel() > $user->getHighestRoleLevel())) {
            return $user;
        }
        abort(403, 'You cannot update a user of the same or higher level than you');
    }
}