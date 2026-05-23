<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard')->with('success', 'Selamat datang!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'user';
        $validated['is_active'] = true;

        $user = User::create($validated);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Registrasi berhasil!');
    }

    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google');
        }

        $existingUser = User::where('google_id', $user->id)->first();

        if ($existingUser) {
            Auth::login($existingUser, true);
            return redirect('/dashboard')->with('success', 'Selamat datang kembali!');
        }

        // Check if email already exists
        $emailUser = User::where('email', $user->email)->first();

        if ($emailUser) {
            // Update existing user with Google info
            $emailUser->update([
                'google_id' => $user->id,
                'google_token' => $user->token,
                'profile_picture' => $user->avatar,
            ]);
            Auth::login($emailUser, true);
            return redirect('/dashboard')->with('success', 'Akun terhubung dengan Google!');
        }

        // Create new user
        $newUser = User::create([
            'name' => $user->name,
            'email' => $user->email,
            'google_id' => $user->id,
            'google_token' => $user->token,
            'profile_picture' => $user->avatar,
            'role' => 'user',
            'is_active' => true,
            'password' => Hash::make(uniqid()), // Generate random password since they use Google
        ]);

        Auth::login($newUser, true);

        return redirect('/dashboard')->with('success', 'Pendaftaran dengan Google berhasil!');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah logout');
    }
}
