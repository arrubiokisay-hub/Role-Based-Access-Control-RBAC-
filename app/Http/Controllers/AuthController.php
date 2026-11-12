<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // --- Admin Login ---
    public function showAdminLogin()
    {
        if (session('role') === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = DB::table('users')
            ->where('username', $credentials['username'])
            ->where('role', 'admin')
            ->first();

        // ✅ FIX: Use Hash::check() to compare properly
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['username' => 'Invalid admin credentials']);
        }

        $request->session()->regenerate();
        $request->session()->put([
            'username' => $user->username,
            'name'     => $user->name ?? $user->username, // ✅ FIX: Fallback if no name column
            'role'     => 'admin',
            'user_id'  => $user->id,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Welcome, Admin!');
    }

    // --- User Login ---
    public function showUserLogin()
    {
        if (session('role') === 'user') {
            return redirect()->route('user.dashboard');
        }
        return view('auth.login');
    }

    public function userLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = DB::table('users')
            ->where('username', $credentials['username'])
            ->where('role', 'user')
            ->first();

        // ✅ FIX: Use Hash::check()
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['username' => 'Invalid credentials']);
        }

        $request->session()->regenerate();
        $request->session()->put([
            'username' => $user->username,
            'name'     => $user->name ?? $user->username, // ✅ Fallback
            'role'     => 'user',
            'user_id'  => $user->id,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Welcome!');
    }

    // --- Show Registration Form ---
    public function showRegister()
    {
        if (session('role') === 'user' || session('role') === 'admin') {
            return redirect()->route('portal');
        }
        return view('auth.register');
    }

    // --- Registration ---
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:100',
            'username'              => 'required|string|min:3|max:50|unique:users,username',
            'password'              => 'required|string|min:6|same:password_confirmation',
            'password_confirmation' => 'required|string|min:6',
        ]);

        // ✅ FIX: Hash the password before saving
        DB::table('users')->insert([
    'username'   => $validated['username'],
    'name'       => $validated['name'],
    'password'   => Hash::make($validated['password']),
    'role'       => 'user',
    'created_at' => now(),
    'updated_at' => now(),
]);

        // Auto-login after register
        $user = DB::table('users')->where('username', $validated['username'])->first();
        
        $request->session()->regenerate();
        $request->session()->put([
            'username' => $user->username,
            'name'     => $user->name ?? $user->username,
            'role'     => 'user',
            'user_id'  => $user->id,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Account created successfully!');
    }

    // --- Logout ---
    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->invalidate();
        return redirect()->route('portal')->with('success', 'Logged out successfully.');
    }

    public function adminLogout(Request $request)
    {
        $request->session()->flush();
        $request->session()->invalidate();
        return redirect()->route('portal')->with('success', 'Logged out successfully.');
    }
}