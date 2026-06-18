<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return $user->role === 'Sekretaris' 
                ? redirect()->route('sekretaris.dashboard')
                : redirect()->route('anggota.dashboard');
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'Sekretaris') {
                return redirect()->route('sekretaris.dashboard')
                    ->with('success', 'Selamat datang kembali, Sekretaris!');
            } else {
                return redirect()->route('anggota.dashboard')
                    ->with('success', 'Selamat datang kembali, ' . ($user->anggota->nama_anggota ?? 'Anggota') . '!');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
