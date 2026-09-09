<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



class AuthController extends Controller
{
    // Display login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Process authentication
    public function login(Request $request)
    {
        // Validate user inputs
        // $request->validate([
        //     'username' => 'required|string',
        // ]);

        // // Поиск пользователя в БД по столбцу 'username' (или 'email', замените под свою структуру)
        // $user = User::where('username', $request->username)->first();

        // // Если пользователь найден, авторизуем его
        // if ($user) {
        //     Auth::login($user, $request->has('remember'));
            
        //     // Редирект на защищенную страницу
        //     return redirect()->intended('/demands');
        // }
        

        // // Return back with error if authentication fails
        // return back()->withErrors([
        //     'email' => 'The provided credentials do not match our records.',
        // ])->withInput();
        


        $request->validate([
            'username' => 'required|string',
        ]);

        $user = User::where('user_login', $request->username)->first();

        if ($user) {
            if ($user->user_locked == 1) {
                return back()->withErrors([
                    'username' => 'Данный аккаунт заблокирован.',
                ])->withInput();
            }

            Auth::login($user, $request->has('remember'));
            $request->session()->regenerate();

            return redirect('/demands');
        }

        return back()->withErrors([
            'username' => 'Пользователь с таким логином не зарегистрирован в системе.',
        ])->withInput();
    }

    // Log out user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

