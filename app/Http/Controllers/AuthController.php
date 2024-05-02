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

        $throttleKey = $this->throttle($request);

        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts = 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            // dd('asdsa ');
            return back()->withErrors(['login' => 'Login Terlalu Cepat. Silahkan Coba Lagi ' . $seconds . ' Detik']);
        }

        $succeslogin = Auth::attempt([
            'nama' => $request->nama,
            'password' => $request->password
        ]);

        if ($succeslogin) {
            RateLimiter::clear($throttleKey);
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil');
        } else {
            RateLimiter::hit($throttleKey);
            return back()->withErrors(['login' => 'Nama Atau Password Salah']);
        }
    }

    public function throttle(Request $request)
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
