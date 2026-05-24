<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\AcModel;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\OrderPhoto;
use App\Models\OrderAddOn;
use App\Models\OrderPayment;
use App\Models\OrderRating;
use App\Models\AddOn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource (User Dashboard).
     */
    public function index()
    {
        $userId = Auth::id();

        // Pesanan yang sedang berjalan (ongoing)
        $ongoingOrders = Order::with('acModel', 'serviceType', 'assignedStaff')
            ->where('user_id', $userId)
            ->whereIn('status', ['menunggu', 'ditugaskan', 'cek_layanan', 'pengerjaan', 'payment'])
            ->latest()
            ->get();

        // Pesanan yang sudah selesai (history)
        $completedOrders = Order::with('acModel', 'serviceType', 'assignedStaff', 'rating')
            ->where('user_id', $userId)
            ->where('status', 'selesai')
            ->latest()
            ->paginate(5);

        return view('user.dashboard', compact('ongoingOrders', 'completedOrders'));
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
        // First, check if the selected service has service types
        $serviceId = $request->input('service_id');
        $hasServiceTypes = false;

        if ($serviceId) {
            $service = Service::find($serviceId);
            $hasServiceTypes = $service && $service->serviceTypes()->count() > 0;
        }

        // Build validation rules based on whether service has types
        $rules = [
            'ac_model_id' => 'required|exists:ac_models,id',
            'service_type_id' => $hasServiceTypes ? 'required|exists:service_types,id' : 'nullable|exists:service_types,id',
            'units' => 'required|integer|min:1',
            'phone' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'visit_date' => 'required|date_format:Y-m-d H:i',
            'notes' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        // Hitung total harga
        $totalPrice = 0;
        if ($validated['service_type_id']) {
            $serviceType = ServiceType::findOrFail($validated['service_type_id']);
            $totalPrice = $serviceType->price * $request->units;
        }

        $order = Order::create(array_merge($validated, [
            'total_price' => $totalPrice,
            'status' => 'menunggu', // Initial status: Menunggu (waiting for admin assignment)
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

        if ($order->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Hanya pesanan dengan status menunggu yang dapat diedit');
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

        if ($order->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Hanya pesanan dengan status menunggu yang dapat diperbarui');
        }

        // First, check if the selected service has service types
        $serviceId = $request->input('service_id');
        $hasServiceTypes = false;

        if ($serviceId) {
            $service = Service::find($serviceId);
            $hasServiceTypes = $service && $service->serviceTypes()->count() > 0;
        }

        // Build validation rules based on whether service has types
        $rules = [
            'ac_model_id' => 'required|exists:ac_models,id',
            'service_type_id' => $hasServiceTypes ? 'required|exists:service_types,id' : 'nullable|exists:service_types,id',
            'units' => 'required|integer|min:1',
            'phone' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'visit_date' => 'required|date_format:Y-m-d H:i',
            'notes' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        // Hitung total harga
        $totalPrice = 0;
        if ($validated['service_type_id']) {
            $serviceType = ServiceType::findOrFail($validated['service_type_id']);
            $totalPrice = $serviceType->price * $request->units;
        }

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

        if ($order->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Hanya pesanan dengan status menunggu yang dapat dibatalkan');
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

        // Show orders with status 'menunggu' (waiting for assignment)
        $unassignedOrders = Order::where('status', 'menunggu')
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
            'status' => 'ditugaskan',
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
            'pending' => Order::assignedTo(Auth::id())->where('status', 'ditugaskan')->count(),
            'confirmed' => Order::assignedTo(Auth::id())->where('status', 'cek_layanan')->count(),
            'selesai' => Order::assignedTo(Auth::id())->where('status', 'selesai')->count(),
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
            'status' => 'required|in:ditugaskan,cek_layanan,pengerjaan,payment,selesai',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', "Status pesanan berhasil diperbarui!");
    }

    /**
     * Show service check form (Cek Layanan stage)
     * Staff akan melakukan pengecekan AC dan upload foto before
     */
    public function showServiceCheckForm(Order $order)
    {
        if (Auth::user()->role !== 'staff' || $order->assigned_staff_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($order->status !== 'ditugaskan') {
            return redirect()->back()->with('error', 'Pesanan harus dalam status ditugaskan untuk melakukan pengecekan layanan');
        }

        return view('orders.service-check', compact('order'));
    }

    /**
     * Submit service check with before photos
     */
    public function submitServiceCheck(Request $request, Order $order)
    {
        if (Auth::user()->role !== 'staff' || $order->assigned_staff_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB per file
            'findings' => 'required|string', // Temuan pengecekan
        ]);

        // Upload before photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('order-photos/before', 'public');
                OrderPhoto::create([
                    'order_id' => $order->id,
                    'type' => 'before',
                    'photo_path' => $path,
                    'description' => $validated['findings'] ?? null,
                ]);
            }
        }

        // Update order status to cek_layanan
        $order->update([
            'status' => 'cek_layanan',
            'service_checked_at' => now(),
            'notes' => $validated['findings'], // Simpan findings di notes atau buat field terpisah
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Pengecekan layanan berhasil disimpan!');
    }

    /**
     * Show work progress form (Pengerjaan stage)
     * Staff akan melakukan pekerjaan dan menambah add-ons
     */
    public function showWorkProgressForm(Order $order)
    {
        if (Auth::user()->role !== 'staff' || $order->assigned_staff_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($order->status !== 'cek_layanan') {
            return redirect()->back()->with('error', 'Pesanan harus dalam status cek layanan untuk melanjutkan pekerjaan');
        }

        $addOns = AddOn::where('is_active', true)->get();
        $currentAddOns = $order->addOns()->with('addOn')->get();

        return view('orders.work-progress', compact('order', 'addOns', 'currentAddOns'));
    }

    /**
     * Submit work progress with after photos and add-ons
     */
    public function submitWorkProgress(Request $request, Order $order)
    {
        if (Auth::user()->role !== 'staff' || $order->assigned_staff_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'add_ons' => 'nullable|array',
            'add_ons.*.id' => 'exists:add_ons,id',
            'add_ons.*.quantity' => 'integer|min:1',
            'work_notes' => 'nullable|string',
        ]);

        // Upload after photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('order-photos/after', 'public');
                OrderPhoto::create([
                    'order_id' => $order->id,
                    'type' => 'after',
                    'photo_path' => $path,
                ]);
            }
        }

        // Add add-ons to order
        $addOnsTotal = 0;
        if (!empty($validated['add_ons'])) {
            foreach ($validated['add_ons'] as $addOnData) {
                $addOn = AddOn::findOrFail($addOnData['id']);
                $quantity = $addOnData['quantity'];
                $subtotal = $addOn->price * $quantity;

                OrderAddOn::create([
                    'order_id' => $order->id,
                    'add_on_id' => $addOn->id,
                    'quantity' => $quantity,
                    'unit_price' => $addOn->price,
                    'subtotal' => $subtotal,
                ]);

                $addOnsTotal += $subtotal;
            }
        }

        // Update order status to pengerjaan
        $newTotalPrice = $order->total_price + $addOnsTotal;
        $order->update([
            'status' => 'pengerjaan',
            'work_completed_at' => now(),
            'total_price' => $newTotalPrice,
        ]);

        // Create payment record
        OrderPayment::create([
            'order_id' => $order->id,
            'total_amount' => $newTotalPrice,
            'status' => 'pending',
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Pekerjaan berhasil disimpan! Lanjut ke pembayaran.');
    }

    /**
     * Show payment form
     */
    public function showPaymentForm(Order $order)
    {
        // Accessible by both staff and customer
        if (Auth::user()->role === 'user' && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (Auth::user()->role === 'staff' && $order->assigned_staff_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($order->status !== 'pengerjaan') {
            return redirect()->back()->with('error', 'Pesanan harus dalam status pengerjaan untuk pembayaran');
        }

        $payment = $order->payment;

        return view('orders.payment', compact('order', 'payment'));
    }

    /**
     * Submit payment
     */
    public function submitPayment(Request $request, Order $order)
    {
        // Accessible by staff and user
        if (Auth::user()->role === 'user' && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (Auth::user()->role === 'staff' && $order->assigned_staff_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,transfer',
            'amount_paid' => 'required|numeric|min:0',
            'bank_name' => 'required_if:payment_method,transfer|string',
            'account_number' => 'required_if:payment_method,transfer|string',
            'account_holder' => 'required_if:payment_method,transfer|string',
            'payment_notes' => 'nullable|string',
        ]);

        $payment = $order->payment;
        $payment->update([
            'payment_method' => $validated['payment_method'],
            'amount_paid' => $validated['amount_paid'],
            'bank_name' => $validated['bank_name'] ?? null,
            'account_number' => $validated['account_number'] ?? null,
            'account_holder' => $validated['account_holder'] ?? null,
            'payment_notes' => $validated['payment_notes'] ?? null,
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        $order->update([
            'status' => 'payment',
            'payment_completed_at' => now(),
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Pembayaran berhasil diproses!');
    }

    /**
     * Show rating form (Selesai stage)
     */
    public function showRatingForm(Order $order)
    {
        if (Auth::user()->role !== 'user' || $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($order->status !== 'payment') {
            return redirect()->back()->with('error', 'Pesanan harus dalam status payment untuk memberikan rating');
        }

        $existingRating = OrderRating::where('order_id', $order->id)->first();

        return view('orders.rating', compact('order', 'existingRating'));
    }

    /**
     * Submit rating
     */
    public function submitRating(Request $request, Order $order)
    {
        if (Auth::user()->role !== 'user' || $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        OrderRating::updateOrCreate(
            ['order_id' => $order->id],
            [
                'user_id' => Auth::id(),
                'staff_id' => $order->assigned_staff_id,
                'rating' => $validated['rating'],
                'review' => $validated['review'] ?? null,
            ]
        );

        $order->update([
            'status' => 'selesai',
            'rated_at' => now(),
        ]);

        return redirect()->route('orders.index')->with('success', 'Rating berhasil disimpan! Terimakasih atas penilaiannya.');
    }
}
