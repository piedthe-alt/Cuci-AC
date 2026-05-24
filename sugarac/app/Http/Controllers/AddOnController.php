<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use Illuminate\Http\Request;

class AddOnController extends Controller
{
    /**
     * Display a listing of add-ons
     */
    public function index()
    {
        $addOns = AddOn::paginate(15);
        return view('admin.add-ons.index', compact('addOns'));
    }

    /**
     * Show the form for creating a new add-on
     */
    public function create()
    {
        return view('admin.add-ons.create');
    }

    /**
     * Store a newly created add-on in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'stock' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        AddOn::create($validated);

        return redirect()->route('admin.add-ons.index')
            ->with('success', 'Add-on berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified add-on
     */
    public function edit(AddOn $addOn)
    {
        return view('admin.add-ons.edit', compact('addOn'));
    }

    /**
     * Update the specified add-on in storage
     */
    public function update(Request $request, AddOn $addOn)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'stock' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $addOn->update($validated);

        return redirect()->route('admin.add-ons.index')
            ->with('success', 'Add-on berhasil diperbarui!');
    }

    /**
     * Remove the specified add-on from storage
     */
    public function destroy(AddOn $addOn)
    {
        $addOn->delete();

        return redirect()->route('admin.add-ons.index')
            ->with('success', 'Add-on berhasil dihapus!');
    }
}
