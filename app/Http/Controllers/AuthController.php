<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loginview()
    {
        return view('pages.login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function Authlogin(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'password' => 'required',
        ]);

        if (RateLimiter::tooManyAttempts($this->throttle($request), $maxAttempts = 3)) {
            $seconds = RateLimiter::availableIn($this->throttle($request));
            // dd($seconds);
            return back()->withErrors(['login' => 'Login Terlalu Cepat. Silahkan Coba Lagi ' . $seconds . ' Detik']);
        }

        $succeslogin = Auth::attempt([
            'nama' => $request->nama,
            'password' => $request->password
        ]);

        if ($succeslogin) {
            RateLimiter::clear($this->throttle($request));
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil');
        } else {
            RateLimiter::hit($this->throttle($request));
            return back()->withErrors(['login' => 'Nama Atau Password Salah']);
        }

    }

    public function throttle(Request $request): string
    {
        return $request->nama . '|' . $request->ip();
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

}
