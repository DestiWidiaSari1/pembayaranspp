<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('loginadmin');
    }

    public function login(Request $request)
    {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {

        // CEK ROLE ADMIN
        if (Auth::user()->role === 'admin') {
            return redirect()->route('dashboard');
        }

        // kalau bukan admin
        Auth::logout();
        return redirect()->route('login')
            ->withErrors(['Akses hanya untuk admin!']);
    }

    return redirect()->route('login')
        ->withErrors(['Email atau password salah!']);
    }
} 
