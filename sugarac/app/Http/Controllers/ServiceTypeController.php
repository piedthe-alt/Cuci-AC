<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\ServiceTypeRegion;
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
        $serviceTypes = ServiceType::with('service', 'regions.province')->paginate(15);
        return view('admin.service-types.index', compact('serviceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($serviceId = null)
    {
        $services = Service::all();
        $selectedService = $serviceId ? Service::find($serviceId) : null;
        $provinces = Province::all();
        return view('admin.service-types.create', compact('services', 'selectedService', 'provinces'));
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
            'region_prices' => 'sometimes|array',
            'region_prices.*.province_id' => 'nullable|exists:provinces,id',
            'region_prices.*.price' => 'nullable|numeric|min:0',
        ]);

        $serviceType = ServiceType::create([
            'service_id' => $validated['service_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
        ]);

        // Save region-specific prices
        if (!empty($validated['region_prices'])) {
            foreach ($validated['region_prices'] as $regionPrice) {
                if (!empty($regionPrice['province_id']) && !empty($regionPrice['price'])) {
                    ServiceTypeRegion::updateOrCreate(
                        [
                            'service_type_id' => $serviceType->id,
                            'province_id' => $regionPrice['province_id'],
                        ],
                        [
                            'price' => $regionPrice['price'],
                        ]
                    );
                }
            }
        }

        return redirect()->route('admin.service-types.index')->with('success', 'Jenis layanan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceType $serviceType)
    {
        $serviceType->load('service', 'regions.province');
        return view('admin.service-types.show', compact('serviceType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceType $serviceType)
    {
        $services = Service::all();
        $provinces = Province::all();
        $serviceType->load('regions.province');

        return view('admin.service-types.edit', compact('serviceType', 'services', 'provinces'));
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
            'region_prices' => 'sometimes|array',
            'region_prices.*.province_id' => 'nullable|exists:provinces,id',
            'region_prices.*.price' => 'nullable|numeric|min:0',
        ]);

        $serviceType->update([
            'service_id' => $validated['service_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
        ]);

        // Update region-specific prices
        // First, delete all existing region prices
        $serviceType->regions()->delete();

        // Add new region prices
        if (!empty($validated['region_prices'])) {
            foreach ($validated['region_prices'] as $regionPrice) {
                if (!empty($regionPrice['province_id']) && !empty($regionPrice['price'])) {
                    ServiceTypeRegion::updateOrCreate(
                        [
                            'service_type_id' => $serviceType->id,
                            'province_id' => $regionPrice['province_id'],
                        ],
                        [
                            'price' => $regionPrice['price'],
                        ]
                    );
                }
            }
        }

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
