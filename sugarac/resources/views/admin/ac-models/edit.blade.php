@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Model AC</h1>
        <p class="text-gray-600">Ubah informasi model AC: {{ $acModel->name }}</p>
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

    <form method="POST" action="{{ route('admin.ac-models.update', $acModel) }}" class="bg-white rounded-lg shadow max-w-2xl">
        @csrf
        @method('PUT')

        <div class="p-8 space-y-6">
            <!-- Nama Model -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-fan mr-1"></i> Nama Model AC
                </label>
                <input type="text" id="name" name="name" value="{{ $acModel->name }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('name')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-1"></i> Deskripsi (Opsional)
                </label>
                <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $acModel->description }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center p-8 bg-gray-50 border-t rounded-b-lg">
            <a href="{{ route('admin.ac-models.show', $acModel) }}" class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <i class="fas fa-arrow-left mr-2"></i> Batal
            </a>
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
