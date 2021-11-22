<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UserController extends Controller
{

    public function index()
    {
        return view('pages.users.table', ['users' => User::all(),]);
    }

    public function create()
    {
        return view('pages.users.create');
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
        return redirect()->route('users.view', ['user' => $user,]);
    }

    public function view(User $user)
    {
        return view('pages.users.view', ['user' => $user,]);
    }

    public function edit(User $user)
    {
        $this->verifyUser($user, true);
        return view('pages.users.update', ['user' => $user,]);
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
        event(new Registered($user));
        $user->save();
        return redirect()->route('users.view', ['user' => $user,]);
    }

    public function destroy(User $user)
    {
        $this->verifyUser($user, false);
        $user->delete();
        return redirect()->route('users.all');
    }

    private function verifyUser($user, $allowSelfEdit) {
        $allow = (Auth::user() instanceof User);
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
}
