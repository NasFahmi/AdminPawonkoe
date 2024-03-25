<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AuthLoginRequest;
use Illuminate\Support\Facades\RateLimiter;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loginview()
    {
        // Rate Limiter
        if (RateLimiter::tooManyAttempts('loginview', $maxAttempts = 4)) {
            $seconds = RateLimiter::availableIn('loginview');
            session()->flash('retryAfter', $seconds);
        } else {
            // Increment Rate Limiter
            RateLimiter::hit('loginview');
        }

        // Mengambil nilai retryAfter jika tersedia
        $retryAfter = session('retryAfter');

        return view('pages.login', compact('retryAfter'));
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

        $succeslogin = Auth::attempt([
            'nama' => $request->nama,
            'password' => $request->password
        ]);
        if ($succeslogin) {
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil');
        } else {
            return back()->withErrors(['login' => 'Nama Atau Password Salah']);
        }

    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

}
