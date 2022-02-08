<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        return view('auth');
    }

    public function auth(Request $request)
    {
        $user = User::whereEmail($request->post('email'))->first();
        if (!$user) {
            return back()->withErrors([
                'email' => 'Не найден такой пользователь',
            ]);
        }

        Auth::login($user);
        return redirect()->route('home');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth');
    }
}
