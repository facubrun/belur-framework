<?php

namespace App\Controllers\Auth;

use App\Models\User;
use Belur\Crypto\Hasher;
use Belur\Http\Controller;
use Belur\Http\Request;

use function Belur\Helpers\app;

class RegisterController extends Controller {
    public function create(Request $request) {
        return view('auth/register', []);
    }

    public function store(Request $request, Hasher $hasher) {
        $data = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
        'confirm_password' => ['required'],
        'name' => ['required']
    ]);

    if($data['password'] != $data['confirm_password']) {
        return back()->withErrors(
            ['confirm_password' => ['confirm_password' => 'Passwords do not match']]);
    }
    $data['password'] = $hasher->hash($data['password']);

    $user = User::create($data);
    
    $user->login();

    return redirect('/');
    }
}