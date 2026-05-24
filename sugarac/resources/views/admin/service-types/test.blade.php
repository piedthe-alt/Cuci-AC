@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Test Harga Regional Pricing</h1>
        <p class="text-gray-600">Halaman ini untuk testing regional pricing</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Form Test -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Form Test</h2>

            <form id="testForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Service ID</label>
                    <select id="serviceId" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="1">Service 1</option>
                        <option value="2">Service 2</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Layanan</label>
                    <input type="text" id="name" placeholder="Nama layanan" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Default</label>
                    <input type="number" id="price" placeholder="Harga default" class="w-full px-4 py-2 border border-gray-300 rounded-lg" step="0.01" min="0">
                </div>

                <div class="border-t pt-4">
                    <h3 class="font-semibold text-gray-800 mb-4">Harga Regional</h3>
                    <div id="regionContainer" class="space-y-3 mb-4"></div>
                    <button type="button" id="addRegionBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        + Tambah Provinsi
                    </button>
                </div>

                <button type="button" id="submitBtn" class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                    Test Submit
                </button>
            </form>
        </div>

        <!-- Display Test Data -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Data yang Akan Dikirim</h2>
            <div id="displayData" class="bg-gray-100 p-4 rounded-lg font-mono text-sm whitespace-pre-wrap break-words">
                Data akan tampil di sini...
            </div>

            <div class="mt-6">
                <h3 class="font-semibold text-gray-800 mb-3">Existing Service Types:</h3>
                <div id="serviceTypesList" class="space-y-2"></div>
            </div>
        </div>
    </div>

    <!-- Existing Data -->
    <div class="mt-8 bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Data di Database</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Service Types</h3>
                <div id="dbServiceTypes" class="space-y-2"></div>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Regional Pricing</h3>
                <div id="dbRegionalPricing" class="space-y-2"></div>
            </div>
        </div>
    </div>
</div>

<template id="regionTemplate">
    <div class="region-row flex gap-2 items-end">
        <select class="province_select flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="">Pilih Provinsi</option>
            @foreach($provinces as $province)
                <option value="{{ $province->id }}">{{ $province->name }}</option>
            @endforeach
        </select>
        <input type="number" class="price_input flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Harga" step="0.01" min="0">
        <button type="button" class="removeBtn px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">X</button>
    </div>
</template>

<script>
const template = document.getElementById('regionTemplate');
const regionContainer = document.getElementById('regionContainer');
const addRegionBtn = document.getElementById('addRegionBtn');
const submitBtn = document.getElementById('submitBtn');
const displayData = document.getElementById('displayData');

function formatData() {
    const regions = [];
    regionContainer.querySelectorAll('.region-row').forEach(row => {
        const provinceId = row.querySelector('.province_select').value;
        const price = row.querySelector('.price_input').value;
        if (provinceId && price) {
            regions.push({
                province_id: provinceId,
                price: price
            });
        }
    });

    const data = {
        service_id: document.getElementById('serviceId').value,
        name: document.getElementById('name').value,
        price: document.getElementById('price').value,
        region_prices: regions
    };

    displayData.textContent = JSON.stringify(data, null, 2);
}

addRegionBtn.addEventListener('click', function(e) {
    e.preventDefault();
    const clone = template.content.cloneNode(true);
    const removeBtn = clone.querySelector('.removeBtn');

    removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        this.closest('.region-row').remove();
        formatData();
    });

    regionContainer.appendChild(clone);
    formatData();
});

submitBtn.addEventListener('click', function(e) {
    e.preventDefault();
    formatData();
    alert('Data sudah diformat. Lihat di sebelah kanan.');
});

// Update display on change
document.getElementById('testForm').addEventListener('change', formatData);
document.getElementById('testForm').addEventListener('input', formatData);

// Load existing data
async function loadExistingData() {
    try {
        const response = await fetch('/api/service-types-data');
        const data = await response.json();

        if (data.serviceTypes) {
            const list = document.getElementById('serviceTypesList');
            data.serviceTypes.forEach(st => {
                const div = document.createElement('div');
                div.className = 'text-sm text-gray-700';
                div.textContent = `${st.name} - Rp ${parseInt(st.price).toLocaleString('id-ID')}`;
                list.appendChild(div);
            });
        }

        if (data.regionalPricing) {
            const div = document.getElementById('dbRegionalPricing');
            if (data.regionalPricing.length > 0) {
                data.regionalPricing.forEach(rp => {
                    const row = document.createElement('div');
                    row.className = 'text-sm text-gray-700';
                    row.textContent = `Service ${rp.service_type_id} → Province ${rp.province_id}: Rp ${parseInt(rp.price).toLocaleString('id-ID')}`;
                    div.appendChild(row);
                });
            } else {
                const row = document.createElement('div');
                row.className = 'text-sm text-gray-500';
                row.textContent = 'Belum ada regional pricing';
                div.appendChild(row);
            }
        }
    } catch (e) {
        console.error('Error loading data:', e);
    }
}

loadExistingData();
</script>
@endsection
