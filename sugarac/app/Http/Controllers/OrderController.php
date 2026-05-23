<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\AcModel;
use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('acModel', 'serviceType')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $acModels = AcModel::all();
        $services = Service::all();
        $serviceTypes = ServiceType::all();

        // Group service types by service untuk JavaScript
        $serviceTypesByService = $serviceTypes->groupBy('service_id')->map(function ($types) {
            return $types->map(function ($type) {
                return [
                    'id' => $type->id,
                    'name' => $type->name,
                    'price' => $type->price,
                    'description' => $type->description,
                ];
            });
        });

        return view('orders.create', compact('acModels', 'services', 'serviceTypes', 'serviceTypesByService'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ac_model_id' => 'required|exists:ac_models,id',
            'service_type_id' => 'required|exists:service_types,id',
            'units' => 'required|integer|min:1',
            'phone' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'visit_date' => 'required|date_format:Y-m-d H:i',
            'notes' => 'nullable|string',
        ]);

        // Hitung total harga
        $serviceType = ServiceType::findOrFail($request->service_type_id);
        $totalPrice = $serviceType->price * $request->units;

        $order = Order::create(array_merge($validated, [
            'total_price' => $totalPrice,
            'status' => 'pending',
            'user_id' => Auth::id(),
        ]));

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat! Silakan menunggu konfirmasi dari admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('acModel', 'serviceType');
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pesanan pending yang dapat diedit');
        }

        $acModels = AcModel::all();
        $serviceTypes = ServiceType::all();
        return view('orders.edit', compact('order', 'acModels', 'serviceTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pesanan pending yang dapat diedit');
        }

        $validated = $request->validate([
            'ac_model_id' => 'required|exists:ac_models,id',
            'service_type_id' => 'required|exists:service_types,id',
            'units' => 'required|integer|min:1',
            'phone' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'visit_date' => 'required|date_format:Y-m-d H:i',
            'notes' => 'nullable|string',
        ]);

        // Hitung total harga
        $serviceType = ServiceType::findOrFail($request->service_type_id);
        $totalPrice = $serviceType->price * $request->units;

        $order->update(array_merge($validated, [
            'total_price' => $totalPrice,
        ]));

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pesanan pending yang dapat dibatalkan');
        }

        $order->update(['status' => 'cancelled']);
        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibatalkan');
    }

    /**
     * Show staff assignments (for admin)
     */
    public function staffAssignments()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $unassignedOrders = Order::unassigned()
            ->with('user', 'acModel', 'serviceType')
            ->latest()
            ->paginate(15);

        return view('orders.assignments.index', compact('unassignedOrders'));
    }

    /**
     * Show assign staff form for a specific order
     */
    public function showAssignForm(Order $order)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $staffs = User::where('role', 'staff')->where('is_active', true)->get();
        return view('orders.assignments.assign', compact('order', 'staffs'));
    }

    /**
     * Assign staff to order
     */
    public function assignStaff(Request $request, Order $order)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'assigned_staff_id' => 'required|exists:users,id',
        ]);

        // Verify that the selected user is staff
        $staff = User::findOrFail($validated['assigned_staff_id']);
        if ($staff->role !== 'staff' || !$staff->is_active) {
            return redirect()->back()->with('error', 'Staff yang dipilih tidak valid atau tidak aktif');
        }

        $order->update([
            'assigned_staff_id' => $validated['assigned_staff_id'],
            'assigned_at' => now(),
            'status' => 'confirmed',
        ]);

        return redirect()->route('orders.assignments')
            ->with('success', "Pesanan berhasil di-assign ke {$staff->name}!");
    }

    /**
     * Show staff dashboard with assigned jobs
     */
    public function staffDashboard()
    {
        if (Auth::user()->role !== 'staff') {
            abort(403, 'Unauthorized');
        }

        $assignedOrders = Order::assignedTo(Auth::id())
            ->with('user', 'acModel', 'serviceType')
            ->orderByDesc('visit_date')
            ->paginate(10);

        $stats = [
            'total_assigned' => Order::assignedTo(Auth::id())->count(),
            'pending' => Order::assignedTo(Auth::id())->where('status', 'pending')->count(),
            'confirmed' => Order::assignedTo(Auth::id())->where('status', 'confirmed')->count(),
            'completed' => Order::assignedTo(Auth::id())->where('status', 'completed')->count(),
        ];

        return view('orders.staff-dashboard', compact('assignedOrders', 'stats'));
    }

    /**
     * Update order status (for staff)
     */
    public function updateStatus(Request $request, Order $order)
    {
        if (Auth::user()->role !== 'staff' || $order->assigned_staff_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:confirmed,completed',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', "Status pesanan berhasil diperbarui!");
    }
}
