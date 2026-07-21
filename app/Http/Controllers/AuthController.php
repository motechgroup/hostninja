<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showAdminLogin()
    {
        return view('auth.admin_login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            if (!Auth::user()->isAdmin()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Access denied. Only system administrators can access the admin console.']);
            }
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back to HostNinja Admin Console!');
        }

        return back()->withErrors(['email' => 'Invalid admin credentials.']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:customer,reseller',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'balance' => 0.00,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome to HostNinja Cloud! Your account is active.');
    }

    public function quickLogin($role)
    {
        $emailMap = [
            'admin' => 'admin@hostninja.cloud',
            'customer' => 'customer@hostninja.cloud',
            'reseller' => 'reseller@hostninja.cloud',
            'agent' => 'agent@hostninja.cloud',
        ];

        $email = $emailMap[$role] ?? 'customer@hostninja.cloud';
        $user = User::where('email', $email)->first() ?? User::where('role', $role)->first();

        if ($user) {
            Auth::login($user);
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Logged in as Administrator (' . $user->name . ')');
            }
            return redirect()->route('dashboard')->with('success', 'Logged in as ' . ucfirst($role) . ' (' . $user->name . ')');
        }

        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
