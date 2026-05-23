@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Pesanan #{{ $order->id }}</h1>
        <p class="text-gray-600">Ubah detail pesanan Anda (hanya pesanan pending yang dapat diedit)</p>
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

    <form method="POST" action="{{ route('orders.update', $order) }}" id="orderForm" class="bg-white rounded-lg shadow">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
            <!-- Bagian Kiri: Input Fields -->
            <div class="space-y-6">
                <!-- Model AC -->
                <div>
                    <label for="ac_model_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-fan mr-1"></i> Pilih Model AC
                    </label>
                    <select id="ac_model_id" name="ac_model_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Model AC --</option>
                        @foreach ($acModels as $model)
                            <option value="{{ $model->id }}" {{ $order->ac_model_id == $model->id ? 'selected' : '' }}>
                                {{ $model->name }} @if($model->description) - {{ $model->description }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('ac_model_id')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Jenis Layanan -->
                <div>
                    <label for="service_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-wrench mr-1"></i> Jenis Layanan
                    </label>
                    <select id="service_type_id" name="service_type_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Layanan --</option>
                        @foreach ($serviceTypes as $type)
                            <option value="{{ $type->id }}" data-price="{{ $type->price }}" {{ $order->service_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} - Rp {{ number_format($type->price, 0, ',', '.') }}/unit
                            </option>
                        @endforeach
                    </select>
                    @error('service_type_id')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Jumlah Unit AC -->
                <div>
                    <label for="units" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-cube mr-1"></i> Jumlah Unit AC
                    </label>
                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                        <button type="button" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600" onclick="decreaseUnits()">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" id="units" name="units" value="{{ $order->units }}" min="1" max="100" required class="flex-1 text-center py-2 border-0 focus:ring-0">
                        <button type="button" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600" onclick="increaseUnits()">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    @error('units')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-1"></i> No. Handphone Aktif
                    </label>
                    <input type="tel" id="phone" name="phone" value="{{ $order->phone }}" placeholder="08123456789" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('phone')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tanggal dan Jam Kunjungan -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-1"></i> Tanggal Kunjungan
                        </label>
                        <input type="date" id="visit_date" name="visit_date" value="{{ $order->visit_date->format('Y-m-d') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('visit_date')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="visit_time" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clock mr-1"></i> Jam Kunjungan
                        </label>
                        <input type="time" id="visit_time" name="visit_time" value="{{ $order->visit_date->format('H:i') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Alamat -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-1"></i> Alamat Lengkap
                    </label>
                    <textarea id="address" name="address" placeholder="Jl. Jalan No. 123, Kelurahan, Kecamatan, Kota, Provinsi" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $order->address }}</textarea>
                    @error('address')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Geolocation -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <button type="button" class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition" onclick="getCurrentLocation()">
                        <i class="fas fa-crosshairs mr-2"></i>
                        Deteksi Lokasi Saat Ini
                    </button>
                    <div id="locationStatus" class="text-sm text-gray-600 mt-2">Lokasi saat ini: {{ $order->latitude }}, {{ $order->longitude }}</div>
                    <input type="hidden" id="latitude" name="latitude" value="{{ $order->latitude }}">
                    <input type="hidden" id="longitude" name="longitude" value="{{ $order->longitude }}">
                </div>

                <!-- Catatan (Opsional) -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note mr-1"></i> Catatan (Opsional)
                    </label>
                    <textarea id="notes" name="notes" placeholder="Tambahkan catatan khusus jika ada..." rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $order->notes }}</textarea>
                    @error('notes')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Bagian Kanan: Summary -->
            <div>
                <div class="sticky top-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-200 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-calculator mr-2"></i> Ringkasan Pesanan
                    </h2>

                    <!-- Selected Items -->
                    <div class="space-y-3 pb-6 border-b border-blue-200">
                        <div class="flex justify-between items-start">
                            <span class="text-gray-700">Model AC:</span>
                            <span id="summaryAcModel" class="font-semibold text-gray-900">-</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-700">Jenis Layanan:</span>
                            <span id="summaryService" class="font-semibold text-gray-900">-</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-700">Jumlah Unit:</span>
                            <span id="summaryUnits" class="font-semibold text-gray-900">{{ $order->units }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-700">Harga per Unit:</span>
                            <span id="summaryPrice" class="font-semibold text-gray-900">-</span>
                        </div>
                    </div>

                    <!-- Total Price -->
                    <div class="my-6 pt-6 border-t border-blue-200">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-lg font-semibold text-gray-800">Total Estimasi:</span>
                            <span class="text-3xl font-bold text-blue-600" id="totalPrice">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-sm text-gray-600 italic">Harga akan diperbarui sesuai dengan pilihan Anda</p>
                    </div>

                    <!-- Service Details -->
                    <div class="bg-white rounded p-4 mt-6">
                        <h3 class="font-semibold text-gray-800 mb-3">
                            <i class="fas fa-info-circle mr-1"></i> Detail Layanan
                        </h3>
                        <ul class="text-sm text-gray-700 space-y-2">
                            <li><i class="fas fa-check text-green-600 mr-2"></i> Pembersihan menyeluruh</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i> Pengecekan teknis</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i> Garansi kepuasan</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i> Teknisi berpengalaman</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center p-8 bg-gray-50 border-t">
            <a href="{{ route('orders.show', $order) }}" class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <i class="fas fa-arrow-left mr-2"></i> Batal
            </a>
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    const acModelSelect = document.getElementById('ac_model_id');
    const serviceTypeSelect = document.getElementById('service_type_id');
    const unitsInput = document.getElementById('units');

    // Update summary
    function updateSummary() {
        // AC Model
        const acModelText = acModelSelect.options[acModelSelect.selectedIndex].text || '-';
        document.getElementById('summaryAcModel').textContent = acModelText.split(' - ')[0];

        // Service Type
        const serviceText = serviceTypeSelect.options[serviceTypeSelect.selectedIndex].text || '-';
        document.getElementById('summaryService').textContent = serviceText.split(' - ')[0];

        // Units
        document.getElementById('summaryUnits').textContent = unitsInput.value || 1;

        // Price calculation
        const selectedOption = serviceTypeSelect.options[serviceTypeSelect.selectedIndex];
        const price = parseFloat(selectedOption.dataset.price) || 0;
        const units = parseInt(unitsInput.value) || 1;
        const totalPrice = price * units;

        document.getElementById('summaryPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
        document.getElementById('totalPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice);
    }

    // Units increment/decrement
    function increaseUnits() {
        unitsInput.value = parseInt(unitsInput.value || 1) + 1;
        updateSummary();
    }

    function decreaseUnits() {
        const current = parseInt(unitsInput.value || 1);
        if (current > 1) {
            unitsInput.value = current - 1;
            updateSummary();
        }
    }

    // Geolocation
    function getCurrentLocation() {
        const statusDiv = document.getElementById('locationStatus');

        if (!navigator.geolocation) {
            statusDiv.textContent = 'Geolokasi tidak didukung oleh browser Anda';
            return;
        }

        statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mendeteksi lokasi...';

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                statusDiv.innerHTML = '<i class="fas fa-check-circle text-green-600"></i> Lokasi terdeteksi: ' + lat.toFixed(4) + ', ' + lng.toFixed(4);
            },
            (error) => {
                statusDiv.textContent = 'Gagal mendeteksi lokasi. Pastikan izin lokasi telah diberikan.';
            }
        );
    }

    // Event listeners
    acModelSelect.addEventListener('change', updateSummary);
    serviceTypeSelect.addEventListener('change', updateSummary);
    unitsInput.addEventListener('change', updateSummary);

    // Initial update
    updateSummary();

    // Combine date and time before submit
    document.getElementById('orderForm').addEventListener('submit', function() {
        const date = document.getElementById('visit_date').value;
        const time = document.getElementById('visit_time').value;
        const dateTimeInput = document.createElement('input');
        dateTimeInput.type = 'hidden';
        dateTimeInput.name = 'visit_date';
        dateTimeInput.value = date + ' ' + time;

        // Remove old visit_date input and add the combined one
        const oldInputs = document.querySelectorAll('input[name="visit_date"]');
        oldInputs.forEach(input => input.remove());
        this.appendChild(dateTimeInput);
    });
</script>
@endsection
