@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Tambah Kategori Layanan Baru</h1>
        <p class="text-gray-600">Buat kategori layanan baru, nanti Anda bisa menambahkan berbagai jenis di dalamnya</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.services.store') }}" class="bg-white rounded-lg shadow max-w-2xl">
        @csrf

        <div class="p-8 space-y-6">
            <!-- Nama Kategori -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-folder mr-1"></i> Nama Kategori Layanan *
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Misal: Cuci AC, Service Mesin Cuci, Servis Lemari Es" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-sm text-gray-600 mt-2">Masukkan nama kategori layanan utama</p>
                @error('name')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-1"></i> Deskripsi (Opsional)
                </label>
                <textarea id="description" name="description" placeholder="Jelaskan kategori layanan ini..." rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Catatan:</strong> Setelah membuat kategori, Anda bisa menambahkan berbagai jenis layanan dengan harga yang berbeda di dalam kategori ini.
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center p-8 bg-gray-50 border-t rounded-b-lg">
            <a href="{{ route('admin.services.index') }}" class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <i class="fas fa-arrow-left mr-2"></i> Batal
            </a>
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                <i class="fas fa-save mr-2"></i> Simpan Kategori
            </button>
        </div>
    </form>
</div>
@endsection
