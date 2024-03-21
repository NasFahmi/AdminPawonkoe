<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
    
}
