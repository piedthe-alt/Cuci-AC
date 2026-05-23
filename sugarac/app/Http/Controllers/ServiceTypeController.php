<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ServiceTypeController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth', 'is-admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $serviceTypes = ServiceType::with('service')->paginate(15);
        return view('admin.service-types.index', compact('serviceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($serviceId = null)
    {
        $services = Service::all();
        $selectedService = $serviceId ? Service::find($serviceId) : null;
        return view('admin.service-types.create', compact('services', 'selectedService'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        ServiceType::create($validated);
        return redirect()->route('admin.service-types.index')->with('success', 'Jenis layanan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceType $serviceType)
    {
        return view('admin.service-types.show', compact('serviceType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceType $serviceType)
    {
        $services = Service::all();
        return view('admin.service-types.edit', compact('serviceType', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceType $serviceType)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $serviceType->update($validated);
        return redirect()->route('admin.service-types.show', $serviceType)->with('success', 'Jenis layanan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceType $serviceType)
    {
        if ($serviceType->orders()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus jenis layanan yang sudah memiliki pesanan');
        }

        $serviceType->delete();
        return redirect()->route('admin.service-types.index')->with('success', 'Jenis layanan berhasil dihapus');
    }
}
