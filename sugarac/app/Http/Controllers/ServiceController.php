<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ServiceController extends BaseController
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
        $services = Service::with('serviceTypes')->paginate(15);
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:services,name',
            'description' => 'nullable|string',
        ]);

        Service::create($validated);
        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $service->load('serviceTypes');
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:services,name,' . $service->id,
            'description' => 'nullable|string',
        ]);

        $service->update($validated);
        return redirect()->route('admin.services.show', $service)->with('success', 'Layanan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        if ($service->serviceTypes()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus layanan yang masih memiliki jenis layanan');
        }

        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil dihapus');
    }
}
