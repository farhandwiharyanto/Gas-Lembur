<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\LdapService;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request, LdapService $ldapService)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        $username = strtolower(trim($request->username));
        $password = $request->password;

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'pimpinan') {
                return redirect()->intended('/pimpinan/dashboard');
            }
            
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'Login gagal, inisial atau password salah',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
