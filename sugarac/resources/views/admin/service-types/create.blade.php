@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Tambah Jenis Layanan Baru</h1>
        <p class="text-gray-600">Tambahkan jenis layanan cuci AC baru dengan harga yang sesuai</p>
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

    <form method="POST" action="{{ route('admin.service-types.store') }}" class="bg-white rounded-lg shadow max-w-2xl">
        @csrf

        <div class="p-8 space-y-6">
            <!-- Pilih Service (Induk) -->
            <div>
                <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-folder mr-1"></i> Kategori Layanan *
                </label>
                <select id="service_id" name="service_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">-- Pilih Kategori Layanan --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ (old('service_id') ?? $selectedService?->id) == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                    @endforeach
                </select>
                @error('service_id')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nama Jenis Layanan -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-wrench mr-1"></i> Nama Jenis Layanan *
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Misal: Cuci Rutin, Service Lengkap, Service Berkala" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('name')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-1"></i> Deskripsi (Opsional)
                </label>
                <textarea id="description" name="description" placeholder="Jelaskan apa yang termasuk dalam layanan ini..." rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Harga -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-money-bill-wave mr-1"></i> Harga per Unit (Rp)
                </label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" placeholder="0" step="0.01" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-sm text-gray-600 mt-2">Masukkan harga per unit AC. Total harga akan dihitung otomatis berdasarkan jumlah unit.</p>
                @error('price')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Regional Pricing Section -->
            <div class="border-t pt-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>Harga per Daerah (Opsional)
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Atur harga khusus untuk setiap provinsi. Jika tidak diatur, akan menggunakan harga default di atas.</p>
                    </div>
                    <button type="button" id="addRegionBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                        <i class="fas fa-plus mr-1"></i>Tambah Provinsi
                    </button>
                </div>

                <!-- Region Prices Container -->
                <div id="regionPricesContainer" class="space-y-4 mt-4">
                    <!-- Template will be cloned here -->
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center p-8 bg-gray-50 border-t rounded-b-lg">
            <a href="{{ route('admin.service-types.index') }}" class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <i class="fas fa-arrow-left mr-2"></i> Batal
            </a>
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                <i class="fas fa-save mr-2"></i> Simpan Layanan
            </button>
        </div>
    </form>
</div>

<!-- Hidden Template for Regional Price -->
<template id="regionPriceTemplate">
    <div class="region-price-row flex gap-4 items-end p-4 bg-blue-50 rounded-lg border border-blue-200">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
            <select class="province_id w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" required>
                <option value="">-- Pilih Provinsi --</option>
                @foreach($provinces as $province)
                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
            <input type="number" class="price_value w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" placeholder="0" step="0.01" min="0" required>
        </div>
        <button type="button" class="removeRegionBtn px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const template = document.getElementById('regionPriceTemplate');
    const container = document.getElementById('regionPricesContainer');
    const addBtn = document.getElementById('addRegionBtn');
    const form = document.querySelector('form');

    function updateHiddenInputs() {
        // Clear existing hidden inputs
        container.querySelectorAll('input[name^="region_prices"], select[name^="region_prices"]').forEach(el => {
            if (!el.closest('.region-price-row')) {
                el.remove();
            }
        });

        // Create hidden inputs for each visible row
        container.querySelectorAll('.region-price-row').forEach((row, index) => {
            const provinceId = row.querySelector('.province_id').value;
            const price = row.querySelector('.price_value').value;

            if (provinceId && price) {
                const provinceInput = document.createElement('input');
                provinceInput.type = 'hidden';
                provinceInput.name = 'region_prices[' + index + '][province_id]';
                provinceInput.value = provinceId;

                const priceInput = document.createElement('input');
                priceInput.type = 'hidden';
                priceInput.name = 'region_prices[' + index + '][price]';
                priceInput.value = price;

                container.appendChild(provinceInput);
                container.appendChild(priceInput);
            }
        });
    }

    addBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const clone = template.content.cloneNode(true);

        const removeBtn = clone.querySelector('.removeRegionBtn');
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            this.closest('.region-price-row').remove();
            updateHiddenInputs();
        });

        container.appendChild(clone);
    });

    // Update hidden inputs before form submission
    form.addEventListener('submit', function(e) {
        updateHiddenInputs();
    });

    // Update when select/input changes
    container.addEventListener('change', function(e) {
        if (e.target.closest('.region-price-row')) {
            updateHiddenInputs();
        }
    });

    // Update when input value changes
    container.addEventListener('input', function(e) {
        if (e.target.closest('.region-price-row')) {
            updateHiddenInputs();
        }
    });

    // Handle remove buttons for dynamically added rows
    container.addEventListener('click', function(e) {
        if (e.target.closest('.removeRegionBtn')) {
            e.preventDefault();
            e.target.closest('.region-price-row').remove();
            updateHiddenInputs();
        }
    });
});
</script>
@endsection
