<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts = 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            // Log activity for rate limited attempts
            activity()
                ->withProperties(['ip' => $request->ip()])
                ->event('login_attempt_rate_limited')
                ->log('Login rate limited for IP ' . $request->ip());

            return back()->withErrors(['login' => 'Login Terlalu Cepat. Silahkan Coba Lagi ' . $seconds . ' Detik']);
        }

        $successLogin = Auth::attempt([
            'nama' => $request->nama,
            'password' => $request->password
        ]);

        if ($successLogin) {
            RateLimiter::clear($throttleKey);
            
            activity()
                ->causedBy(auth()->user())
                ->withProperties(['ip' => $request->ip()])
                ->event('login_succesfull')
                ->log('User ' . auth()->user()->nama . ' logged into account from IP ' . $request->ip());
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

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip' => $request->ip()])
            ->event('logout_succesfull')
            ->log('User ' . auth()->user()->nama . ' logout from account with IP ' . $request->ip());

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
