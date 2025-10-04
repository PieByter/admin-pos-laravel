<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                // Validasi input
                $request->validate([
                    'email' => 'required|email',
                    'password' => 'required',
                ], [
                    'email.required' => 'Email wajib diisi.',
                    'email.email' => 'Format email tidak valid.',
                    'password.required' => 'Password wajib diisi.',
                ]);

                $email = strtolower($request->email);
                $password = $request->password;
                $remember = $request->has('remember');

                // Cari user berdasarkan email
                $user = User::where('email', $email)->first();

                if ($user && Hash::check($password, $user->password)) {
                    // Login user
                    Auth::login($user, $remember);

                    // Set session data tambahan (opsional)
                    session([
                        'user_id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'role' => $user->role,
                        'photo' => $user->photo,
                    ]);

                    // Set permissions ke session (seperti di CodeIgniter)
                    $permissions = $user->getAllPermissions()->pluck('name')->toArray();
                    session(['permissions' => $permissions]);

                    // Log aktivitas login
                    try {
                        ActivityLog::create([
                            'user_id' => $user->id,
                            'activity' => 'Login ke sistem',
                            'created_at' => now(),
                        ]);
                    } catch (\Exception $logError) {
                        Log::error('Error logging login: ' . $logError->getMessage());
                    }

                    return redirect()->intended('/dashboard')->with('success', 'Login berhasil!');
                } else {
                    return back()
                        ->withInput()
                        ->with('error', 'Email atau Password salah');
                }
            } catch (\Exception $e) {
                Log::error('Login error: ' . $e->getMessage());
                return back()
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
            }
        }

        return view('auth.login');
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                // Validasi input yang lebih ketat
                $request->validate([
                    'username' => 'required|min:4|max:32|unique:users,username',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9])/',
                    'password_confirmation' => 'required|same:password',
                ], [
                    'username.required' => 'Username wajib diisi.',
                    'username.min' => 'Username minimal 4 karakter.',
                    'username.max' => 'Username maksimal 32 karakter.',
                    'username.unique' => 'Username sudah digunakan.',
                    'email.required' => 'Email wajib diisi.',
                    'email.email' => 'Format email tidak valid.',
                    'email.unique' => 'Email sudah digunakan.',
                    'password.required' => 'Password wajib diisi.',
                    'password.min' => 'Password minimal 8 karakter.',
                    'password.regex' => 'Password harus mengandung huruf besar, kecil, dan simbol.',
                    'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
                    'password_confirmation.same' => 'Konfirmasi password harus sama dengan password.',
                ]);

                // Buat user baru
                $user = User::create([
                    'username' => $request->username,
                    'email' => strtolower($request->email),
                    'password' => Hash::make($request->password),
                    'role' => 'viewer', // atau 'useradmin' sesuai kebutuhan
                ]);

                // Assign role default
                $user->assignRole('viewer');

                // Log aktivitas pendaftaran
                try {
                    ActivityLog::create([
                        'user_id' => $user->id,
                        'activity' => 'Mendaftar sebagai user baru',
                        'created_at' => now(),
                    ]);
                } catch (\Exception $logError) {
                    Log::error('Error logging registration: ' . $logError->getMessage());
                }

                return redirect()->route('login')
                    ->with('success', 'Pendaftaran berhasil! Silakan login dengan email Anda.');
            } catch (\Exception $e) {
                Log::error('Registration error: ' . $e->getMessage());
                return back()
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage());
            }
        }

        return view('auth.register');
    }

    public function logout()
    {
        $user = Auth::user();

        if ($user) {
            // Log aktivitas logout
            try {
                ActivityLog::create([
                    'user_id' => $user->id,
                    'activity' => 'Logout dari sistem',
                    'created_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::error('Error logging logout: ' . $e->getMessage());
            }
        }

        // Logout dan hapus session
        Auth::logout();
        session()->flush();
        session()->regenerate();

        return redirect()->route('login')
            ->with('success', 'Logout berhasil!');
    }
}
