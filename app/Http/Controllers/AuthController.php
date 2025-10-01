<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                return redirect()->intended('dashboard');
            }
            return back()->withErrors(['email' => 'Email atau password salah']);
        }
        return view('auth.login');
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'username' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:4',
            ]);
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            // Assign role 'viewer' ke user baru
            $user->assignRole('viewer');
            // Jika ingin assign permission langsung:
            // $user->givePermissionTo('nama-permission');
            return redirect('auth/login')->with('success', 'Registrasi berhasil, silakan login.');
        }
        return view('auth.register');
    }

    public function logout()
    {
        Auth::logout();
        return view('auth.login');
    }
}