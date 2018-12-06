<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login()
    {
        if (! Auth::attempt(request(['email', 'password']))) {
            return redirect('/login')->withInput(request(['email']))->withErrors([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        return redirect('/backstage/concerts');
    }

    public function logout()
    {
        Auth::logout();
        session()->regenerate();

        return redirect('/login');
    }
}
