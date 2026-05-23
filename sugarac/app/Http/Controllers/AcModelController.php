<?php

namespace App\Http\Controllers;

use App\Models\AcModel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class AcModelController extends BaseController
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
        $acModels = AcModel::paginate(15);
        return view('admin.ac-models.index', compact('acModels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ac-models.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:ac_models,name',
            'description' => 'nullable|string',
        ]);

        AcModel::create($validated);
        return redirect()->route('admin.ac-models.index')->with('success', 'Model AC berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(AcModel $acModel)
    {
        return view('admin.ac-models.show', compact('acModel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcModel $acModel)
    {
        return view('admin.ac-models.edit', compact('acModel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcModel $acModel)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:ac_models,name,' . $acModel->id,
            'description' => 'nullable|string',
        ]);

        $acModel->update($validated);
        return redirect()->route('admin.ac-models.show', $acModel)->with('success', 'Model AC berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcModel $acModel)
    {
        if ($acModel->orders()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus model AC yang sudah memiliki pesanan');
        }

        $acModel->delete();
        return redirect()->route('admin.ac-models.index')->with('success', 'Model AC berhasil dihapus');
    }
}
