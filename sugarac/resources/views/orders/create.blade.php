@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Buat Pesanan Cuci AC</h1>
        <p class="text-gray-600">Isi formulir di bawah untuk membuat pesanan layanan cuci AC</p>
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

    <form method="POST" action="{{ route('orders.store') }}" id="orderForm" class="bg-white rounded-lg shadow">
        @csrf

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
                            <option value="{{ $model->id }}" {{ old('ac_model_id') == $model->id ? 'selected' : '' }}>
                                {{ $model->name }} @if($model->description) - {{ $model->description }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('ac_model_id')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Kategori Layanan -->
                <div>
                    <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-folder mr-1"></i> Kategori Layanan
                    </label>
                    <select id="service_id" name="service_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Kategori Layanan --</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis Layanan -->
                <div id="serviceTypeContainer" class="hidden">
                    <label for="service_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-wrench mr-1"></i> Jenis Layanan
                        <span id="serviceTypeRequired" class="text-red-500">*</span>
                    </label>
                    <select id="service_type_id" name="service_type_id" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50">
                        <option value="">-- Pilih Kategori Layanan Dulu --</option>
                    </select>
                    <p id="serviceTypeHint" class="text-xs text-gray-500 mt-1"></p>
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
                        <input type="number" id="units" name="units" value="{{ old('units', 1) }}" min="1" max="100" required class="flex-1 text-center py-2 border-0 focus:ring-0">
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
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" placeholder="08123456789" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                        <input type="date" id="visit_date" name="visit_date" value="{{ old('visit_date') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('visit_date')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="visit_time" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clock mr-1"></i> Jam Kunjungan
                        </label>
                        <input type="time" id="visit_time" name="visit_time" value="{{ old('visit_time') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Alamat -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-1"></i> Alamat Lengkap
                    </label>
                    <textarea id="address" name="address" placeholder="Jl. Jalan No. 123, Kelurahan, Kecamatan, Kota, Provinsi" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address', Auth::user()->address ?? '') }}</textarea>
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
                    <div id="locationStatus" class="text-sm text-gray-600 mt-2"></div>
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                </div>

                <!-- Catatan (Opsional) -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note mr-1"></i> Catatan (Opsional)
                    </label>
                    <textarea id="notes" name="notes" placeholder="Tambahkan catatan khusus jika ada..." rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
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
                            <span id="summaryUnits" class="font-semibold text-gray-900">1</span>
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
                            <span class="text-3xl font-bold text-blue-600" id="totalPrice">Rp 0</span>
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
            <a href="{{ route('orders.index') }}" class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                <i class="fas fa-check mr-2"></i> Buat Pesanan
            </button>
        </div>
    </form>
</div>

<script>
    // Data service types grouped by service
    const serviceTypesByService = {!! json_encode($serviceTypesByService) !!};

    const acModelSelect = document.getElementById('ac_model_id');
    const serviceSelect = document.getElementById('service_id');
    const serviceTypeSelect = document.getElementById('service_type_id');
    const unitsInput = document.getElementById('units');

    // Filter service types based on selected service
    function filterServiceTypes() {
        const selectedServiceId = serviceSelect.value;
        const currentServiceTypeValue = serviceTypeSelect.value;
        const serviceTypeRequired = document.getElementById('serviceTypeRequired');
        const serviceTypeHint = document.getElementById('serviceTypeHint');
        const serviceTypeContainer = document.getElementById('serviceTypeContainer');

        if (!selectedServiceId) {
            // Jika tidak ada kategori dipilih, hide dan disable dropdown
            serviceTypeContainer.classList.add('hidden');
            serviceTypeSelect.disabled = true;
            serviceTypeSelect.removeAttribute('required');
            serviceTypeSelect.innerHTML = '<option value="">-- Pilih Kategori Layanan Dulu --</option>';
            serviceTypeSelect.value = '';
            updateSummary();
            return;
        }

        // Show the container
        serviceTypeContainer.classList.remove('hidden');
        serviceTypeSelect.disabled = false;

        // Get default option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = '-- Pilih Jenis Layanan --';

        // Clear current options
        serviceTypeSelect.innerHTML = '';
        serviceTypeSelect.appendChild(defaultOption);

        if (serviceTypesByService[selectedServiceId] && serviceTypesByService[selectedServiceId].length > 0) {
            // Service has types - make it required
            serviceTypeSelect.setAttribute('required', 'required');
            serviceTypeRequired.classList.remove('hidden');
            serviceTypeHint.textContent = '';

            // Add filtered options
            serviceTypesByService[selectedServiceId].forEach(type => {
                const option = document.createElement('option');
                option.value = type.id;
                option.dataset.price = type.price;
                option.dataset.serviceId = selectedServiceId;
                option.textContent = `${type.name} - Rp ${new Intl.NumberFormat('id-ID').format(type.price)}/unit`;
                serviceTypeSelect.appendChild(option);
            });

            // Try to restore previous selection if it belongs to this service
            if (currentServiceTypeValue) {
                serviceTypeSelect.value = currentServiceTypeValue;
            }
        } else {
            // Service has no types - hide the container again
            serviceTypeContainer.classList.add('hidden');
            serviceTypeSelect.removeAttribute('required');
            serviceTypeRequired.classList.add('hidden');
            serviceTypeHint.textContent = '';
            serviceTypeSelect.value = '';
        }

        updateSummary();
    }

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

    // Event listeners
    acModelSelect.addEventListener('change', updateSummary);
    serviceSelect.addEventListener('change', filterServiceTypes);
    serviceTypeSelect.addEventListener('change', updateSummary);
    unitsInput.addEventListener('change', updateSummary);

    // Initialize: check if category was previously selected
    window.addEventListener('load', function() {
        if (serviceSelect.value) {
            filterServiceTypes();
        }
        updateSummary();
    });

    // Geolocation & Reverse Geocoding
    function getCurrentLocation() {
        const statusDiv = document.getElementById('locationStatus');
        const addressInput = document.getElementById('address');

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

                // Show coordinates while fetching address
                statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengambil alamat dari koordinat: ' + lat.toFixed(4) + ', ' + lng.toFixed(4);

                // Fetch address from Nominatim (OpenStreetMap)
                fetchAddressFromCoordinates(lat, lng, statusDiv, addressInput);
            },
            (error) => {
                statusDiv.textContent = 'Gagal mendeteksi lokasi. Pastikan izin lokasi telah diberikan.';
            }
        );
    }

    // Fetch address from Nominatim API (OpenStreetMap)
    function fetchAddressFromCoordinates(lat, lng, statusDiv, addressInput) {
        const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1&accept-language=id`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const address = data.address;

                // Build complete formatted address
                let fullAddress = '';
                let displayAddress = '';

                // 1. Jalan & Nomor (Street & House Number)
                let streetPart = '';
                if (address.road) {
                    streetPart = address.road;
                    if (address.house_number) {
                        streetPart = `Jl. ${address.road} No. ${address.house_number}`;
                    } else {
                        streetPart = `Jl. ${address.road}`;
                    }
                } else if (address.pedestrian) {
                    // Fallback: pedestrian area
                    streetPart = address.pedestrian;
                } else if (address.footway) {
                    // Fallback: footway
                    streetPart = address.footway;
                } else if (address.path) {
                    // Fallback: path
                    streetPart = address.path;
                } else {
                    // Fallback: jika tidak ada jalan, gunakan koordinat
                    streetPart = `Koordinat: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                }

                // 2. RT/RW
                let rtRwPart = '';
                if (address.hamlet) {
                    rtRwPart = address.hamlet; // Sometimes RT/RW is stored here
                }

                // 3. Kelurahan/Village
                let kelurahanPart = '';
                if (address.village) {
                    kelurahanPart = `Kel. ${address.village}`;
                } else if (address.suburb) {
                    kelurahanPart = `Kel. ${address.suburb}`;
                }

                // 4. Kecamatan/District
                let kecamatanPart = '';
                if (address.county && address.county !== address.village) {
                    kecamatanPart = `Kec. ${address.county}`;
                }

                // 5. Kota/City
                let kotaPart = '';
                if (address.city) {
                    kotaPart = address.city;
                } else if (address.town) {
                    kotaPart = address.town;
                }

                // 6. Provinsi/State
                let provinsiPart = '';
                if (address.state) {
                    provinsiPart = address.state;
                }

                // 7. Kode Pos/Postal Code
                let postalPart = '';
                if (address.postcode) {
                    postalPart = address.postcode;
                }

                // Build full address with proper formatting
                const parts = [];
                if (streetPart) parts.push(streetPart);
                if (rtRwPart) parts.push(rtRwPart);
                if (kelurahanPart) parts.push(kelurahanPart);
                if (kecamatanPart) parts.push(kecamatanPart);
                if (kotaPart) parts.push(kotaPart);
                if (provinsiPart) parts.push(provinsiPart);

                fullAddress = parts.join(', ');
                if (postalPart) {
                    fullAddress += `\nKode Pos: ${postalPart}`;
                }

                // Build display address (more compact)
                const displayParts = [];
                if (streetPart) displayParts.push(streetPart);
                if (kelurahanPart) displayParts.push(kelurahanPart);
                if (kecamatanPart) displayParts.push(kecamatanPart);
                if (kotaPart) displayParts.push(kotaPart);
                if (provinsiPart) displayParts.push(provinsiPart);
                displayAddress = displayParts.join(', ');
                if (postalPart) {
                    displayAddress += ` (${postalPart})`;
                }

                // Check if street was found
                const noStreetFound = streetPart.includes('Koordinat:');

                // Update address field if empty
                if (!addressInput.value.trim()) {
                    addressInput.value = fullAddress;
                }

                // Show success message with formatted address
                let statusMessage = '<i class="fas fa-check-circle text-green-600"></i> Lokasi terdeteksi: ' + lat.toFixed(4) + ', ' + lng.toFixed(4) +
                    '<br><small class="text-gray-600">Alamat: ' + displayAddress + '</small>';

                // Add warning if street not found
                if (noStreetFound) {
                    statusMessage += '<br><small class="text-orange-600 font-semibold">⚠️ Nama jalan tidak ditemukan di database peta. Silakan lengkapi alamat secara manual.</small>';
                }

                statusDiv.innerHTML = statusMessage;
            })
            .catch(error => {
                // If reverse geocoding fails, still show the coordinates
                statusDiv.innerHTML = '<i class="fas fa-check-circle text-green-600"></i> Lokasi terdeteksi: ' + lat.toFixed(4) + ', ' + lng.toFixed(4) +
                    '<br><small class="text-yellow-600">⚠️ Gagal mengambil detail alamat, silakan isi manual</small>';
                console.error('Reverse geocoding error:', error);
            });
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
        const oldInput = document.querySelector('input[name="visit_date"]');
        oldInput.remove();
        this.appendChild(dateTimeInput);
    });
</script>
@endsection
