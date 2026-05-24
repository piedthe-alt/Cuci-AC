<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show user profile
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Show staff dashboard (untuk halaman awal staff setelah login)
     */
    public function staffProfile()
    {
        $user = Auth::user();

        if ($user->role !== 'staff') {
            abort(403, 'Unauthorized');
        }

        // Get staff statistics
        $assignedOrders = $user->assignedOrders()->count();
        $completedOrders = $user->assignedOrders()->where('status', 'selesai')->count();
        $avgRating = $user->receivedRatings()->avg('rating') ?? 0;
        $totalRatings = $user->receivedRatings()->count();

        return view('staff.profile', compact('user', 'assignedOrders', 'completedOrders', 'avgRating', 'totalRatings'));
    }

    /**
     * Show user dashboard
     */
    public function userProfile()
    {
        $user = Auth::user();

        if ($user->role !== 'user') {
            abort(403, 'Unauthorized');
        }

        // Get user's order statistics
        $totalOrders = $user->orders()->count();
        $completedOrders = $user->orders()->where('status', 'selesai')->count();
        $ongoingOrders = $user->orders()->whereNotIn('status', ['selesai', 'cancelled'])->count();

        return view('user.profile', compact('user', 'totalOrders', 'completedOrders', 'ongoingOrders'));
    }

    /**
     * Change password
     */
    public function changePassword()
    {
        return view('profile.change-password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Password berhasil diubah!');
    }
}
