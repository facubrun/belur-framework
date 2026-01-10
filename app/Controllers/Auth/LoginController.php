<?php

namespace App\Controllers\Auth;

use App\Models\User;
use Belur\Auth\Auth;
use Belur\Crypto\Hasher;
use Belur\Http\Controller;
use Belur\Http\Request;

class LoginController extends Controller {
    public function create() {
        return view('auth/login', []);
    }

    public function store(Request $request, Hasher $hasher) {
            $data = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $user = User::firstWhere('email', $data['email']);

    if (is_null($user) || !$hasher->verify($data['password'], $user->password)) {
        return back()->withErrors(['email' => ['email' => 'Email not found']]);
    }

    $user->login();
    return redirect('/');
    }

    public function destroy() {
        Auth::logout();
        return redirect('/');
    }
}