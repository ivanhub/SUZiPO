<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // $request->validate([
        //     'username' => 'required|string',
        // ]);

        // $user = User::where('user_login', $request->username)->first();

        // if ($user) {
        //     if ($user->user_locked == 1) {
        //         return back()->withErrors([
        //             'username' => 'Данный аккаунт заблокирован.',
        //         ])->withInput();
        //     }

        //     Auth::login($user, $request->has('remember'));
        //     $request->session()->regenerate();

        //     return redirect('/demands');
        // }

        // return back()->withErrors([
        //     'username' => 'Пользователь с таким логином не зарегистрирован в системе.',
        // ])->withInput();
        $mockUser = \App\Models\User::find(1) ?? new \App\Models\User();

        if ($mockUser) {
            Auth::login($mockUser);
            $request->session()->regenerate();
            return redirect('/demands');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
