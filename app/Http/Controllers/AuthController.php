<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            if (Auth::user()->role == 1) {
                return redirect()->route('dashboard.index')->with('success', 'Login successfully!');
            } else {
                return redirect()->route('category.index');
            }
        }
        return view('back-end.login');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.index');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        if ($validator->passes()) {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                if (Auth::user()->role == 1) {
                    return redirect()->route('dashboard.index')->with('suscess', 'Login successfully!');
                } else {
                    return redirect()->route('category.index');
                }
            } else {
                return redirect()->back()->with(['error' => 'Email or password is incorrect']);
            }
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
}
