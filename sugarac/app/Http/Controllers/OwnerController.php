<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\OrderRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    /**
     * Show owner dashboard with financial reports
     */
    public function dashboard()
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized');
        }

        // Financial Overview
        $totalRevenue = Order::where('status', 'selesai')
            ->sum('total_price');

        $thisMonthRevenue = Order::where('status', 'selesai')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'selesai')->count();

        // Staff Performance
        $staffPerformance = User::where('role', 'staff')
            ->where('is_active', true)
            ->get()
            ->map(function ($staff) {
                $assignedOrders = Order::where('assigned_staff_id', $staff->id)->count();
                $completedOrders = Order::where('assigned_staff_id', $staff->id)
                    ->where('status', 'selesai')
                    ->count();

                $avgRating = OrderRating::where('staff_id', $staff->id)
                    ->avg('rating') ?? 0;

                return [
                    'id' => $staff->id,
                    'name' => $staff->name,
                    'assigned_orders' => $assignedOrders,
                    'completed_orders' => $completedOrders,
                    'avg_rating' => round($avgRating, 2),
                ];
            });

        // Monthly revenue trend (last 6 months)
        $monthlyRevenue = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Order::where('status', 'selesai')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_price');

            $monthlyRevenue->push([
                'month' => $date->format('M Y'),
                'revenue' => $revenue,
            ]);
        }

        return view('owner.dashboard', compact(
            'totalRevenue',
            'thisMonthRevenue',
            'totalOrders',
            'completedOrders',
            'staffPerformance',
            'monthlyRevenue'
        ));
    }

    /**
     * Show detailed staff ratings
     */
    public function staffRatings()
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized');
        }

        $staffRatings = User::where('role', 'staff')
            ->where('is_active', true)
            ->get()
            ->map(function ($staff) {
                $ratings = OrderRating::where('staff_id', $staff->id)
                    ->with('order', 'user')
                    ->latest()
                    ->get();

                $avgRating = $ratings->avg('rating') ?? 0;
                $totalRatings = $ratings->count();

                return [
                    'id' => $staff->id,
                    'name' => $staff->name,
                    'avg_rating' => round($avgRating, 2),
                    'total_ratings' => $totalRatings,
                    'ratings' => $ratings,
                ];
            })
            ->sortByDesc('avg_rating');

        return view('owner.staff-ratings', compact('staffRatings'));
    }

    /**
     * Show financial reports
     */
    public function financialReport()
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized');
        }

        $totalRevenue = Order::where('status', 'selesai')
            ->sum('total_price');

        $revenueByServiceType = Order::where('status', 'selesai')
            ->with('serviceType')
            ->get()
            ->groupBy('service_type_id')
            ->map(function ($orders) {
                return [
                    'service' => $orders[0]->serviceType->name ?? 'Unknown',
                    'count' => $orders->count(),
                    'total' => $orders->sum('total_price'),
                    'average' => $orders->avg('total_price'),
                ];
            });

        $dailyRevenue = Order::where('status', 'selesai')
            ->selectRaw('DATE(updated_at) as date, SUM(total_price) as total')
            ->groupByRaw('DATE(updated_at)')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        return view('owner.financial-report', compact(
            'totalRevenue',
            'revenueByServiceType',
            'dailyRevenue'
        ));
    }
}
