<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $rules = [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6',
        ];

        $messages = [
            "email.exists" => "This email has not been registered",
        ];

        $validator = Validator::make($credentials, $rules, $messages);
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator->messages());
        }

        if (!Auth::guard('web-administrator')->attempt($credentials)) {
            return Redirect::back()->withInput()->withErrors([
                'password' => 'Your password is wrong',
            ]);
        }

        return redirect('/admin/dashboard')->withSuccess('Signed in successfully');
    }

    public function logout()
    {
        Auth::guard('web-administrator')->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
