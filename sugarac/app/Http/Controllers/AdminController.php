<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalStaff = User::where('role', 'staff')->count();
        $totalRegularUsers = User::where('role', 'user')->count();

        // Get unassigned orders info
        $unassignedCount = Order::unassigned()->count();
        $recentUnassignedOrders = Order::unassigned()
            ->with('user', 'acModel', 'serviceType')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalAdmins' => $totalAdmins,
            'totalStaff' => $totalStaff,
            'totalRegularUsers' => $totalRegularUsers,
            'unassignedCount' => $unassignedCount,
            'recentUnassignedOrders' => $recentUnassignedOrders,
        ]);
    }

    /**
     * List all users
     */
    public function listUsers()
    {
        $users = User::paginate(15);
        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * Show user detail
     */
    public function showUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', ['user' => $user]);
    }

    /**
     * Edit user
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', ['user' => $user]);
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'role' => 'required|in:user,staff,admin',
            'is_active' => 'required|boolean',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $id)
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting the only admin
        if ($user->isAdmin() && User::where('role', 'admin')->count() === 1) {
            return back()->with('error', 'Tidak bisa menghapus satu-satunya admin!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }

    /**
     * Change user role
     */
    public function changeUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'role' => 'required|in:user,staff,admin',
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()->back()
            ->with('success', 'Role pengguna berhasil diubah!');
    }

    /**
     * Toggle user active status
     */
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Pengguna berhasil {$status}!");
    }

    /**
     * Show work management dashboard for assigned orders
     */
    public function workManagement()
    {
        // Get assigned orders by status
        $pendingOrders = Order::assigned()
            ->where('status', 'pending')
            ->with('user', 'acModel', 'serviceType', 'assignedStaff')
            ->orderBy('visit_date', 'asc')
            ->get();

        $confirmedOrders = Order::assigned()
            ->where('status', 'confirmed')
            ->with('user', 'acModel', 'serviceType', 'assignedStaff')
            ->orderBy('visit_date', 'asc')
            ->get();

        $completedOrders = Order::assigned()
            ->where('status', 'completed')
            ->with('user', 'acModel', 'serviceType', 'assignedStaff')
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        // Calculate statistics
        $stats = [
            'total_assigned' => Order::assigned()->count(),
            'pending' => $pendingOrders->count(),
            'confirmed' => $confirmedOrders->count(),
            'completed' => $completedOrders->count(),
            'active_staff' => User::where('role', 'staff')
                ->whereHas('assignedOrders')
                ->distinct()
                ->count(),
        ];

        // Get active staff members (those with assigned orders)
        $activeStaff = User::where('role', 'staff')
            ->whereHas('assignedOrders')
            ->with(['assignedOrders' => function ($query) {
                $query->select('assigned_staff_id', 'status')->distinct();
            }])
            ->get();

        // Calculate per-staff statistics
        $staffStats = [];
        foreach ($activeStaff as $staff) {
            $staffStats[$staff->id] = [
                'total' => Order::assignedTo($staff->id)->count(),
                'pending' => Order::assignedTo($staff->id)->where('status', 'pending')->count(),
                'confirmed' => Order::assignedTo($staff->id)->where('status', 'confirmed')->count(),
                'completed' => Order::assignedTo($staff->id)->where('status', 'completed')->count(),
            ];
        }

        // Get recent activities
        $recentActivities = Order::assigned()
            ->with('user', 'acModel', 'serviceType', 'assignedStaff')
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        return view('admin.work-management', [
            'pendingOrders' => $pendingOrders,
            'confirmedOrders' => $confirmedOrders,
            'completedOrders' => $completedOrders,
            'stats' => $stats,
            'activeStaff' => $activeStaff,
            'staffStats' => $staffStats,
            'recentActivities' => $recentActivities,
        ]);
    }
}
