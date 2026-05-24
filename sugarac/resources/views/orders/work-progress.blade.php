@extends('layouts.app')

@section('title', 'Pengerjaan AC')

@section('content')
<div class="container mx-auto px-6 py-8">
    <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Pesanan
    </a>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-wrench text-orange-600 mr-2"></i> Pengerjaan AC
                </h1>
                <p class="text-gray-600">{{ $order->acModel->name }} - {{ $order->serviceType->name }}</p>
            </div>

            <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded mb-6">
                <p class="text-orange-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Instruksi:</strong> Lakukan pekerjaan, catat perlengkapan yang digunakan, dan upload foto hasil pekerjaan. Minimal satu foto setelah pekerjaan harus di-upload.
                </p>
            </div>

            <!-- Order Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 bg-gray-50 p-6 rounded-lg">
                <div>
                    <p class="text-sm text-gray-600">Pelanggan</p>
                    <p class="font-bold text-gray-900">{{ $order->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Model AC</p>
                    <p class="font-bold text-gray-900">{{ $order->acModel->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Layanan</p>
                    <p class="font-bold text-gray-900">{{ $order->serviceType->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jumlah Unit</p>
                    <p class="font-bold text-gray-900">{{ $order->units }} Unit</p>
                </div>
            </div>

            <form action="{{ route('orders.submit-work-progress', $order) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Add-ons Section -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-box text-orange-600 mr-2"></i> Perlengkapan yang Digunakan
                    </h2>

                    <!-- Add-on List -->
                    <div id="addOnsContainer" class="space-y-3 mb-6">
                        @if($currentAddOns->count() > 0)
                            @foreach($currentAddOns as $orderAddOn)
                                <div class="add-on-item bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-end gap-4">
                                    <div class="flex-1">
                                        <input type="hidden" name="add_ons[{{ $loop->index }}][id]" value="{{ $orderAddOn->addOn->id }}">
                                        <p class="text-sm text-gray-600 mb-1">Perlengkapan</p>
                                        <p class="font-semibold text-gray-900">{{ $orderAddOn->addOn->name }}</p>
                                    </div>
                                    <div class="w-20">
                                        <p class="text-sm text-gray-600 mb-1">Qty</p>
                                        <input type="number" name="add_ons[{{ $loop->index }}][quantity]" class="w-full px-3 py-2 border border-gray-300 rounded qty-input" value="{{ $orderAddOn->quantity }}" min="1" onchange="calculateSubtotal(this)">
                                    </div>
                                    <div class="w-32">
                                        <p class="text-sm text-gray-600 mb-1">Harga/Unit</p>
                                        <p class="font-semibold text-gray-900">Rp {{ number_format($orderAddOn->addOn->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="w-32">
                                        <p class="text-sm text-gray-600 mb-1">Subtotal</p>
                                        <p class="font-bold text-orange-600">Rp {{ number_format($orderAddOn->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                    <button type="button" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition" onclick="this.closest('.add-on-item').remove()">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Add Add-on Button -->
                    <div class="bg-white border border-dashed border-gray-300 rounded-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label for="addOnSelect" class="block text-sm font-semibold text-gray-800 mb-2">Tambah Perlengkapan</label>
                                <select id="addOnSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    <option value="">-- Pilih Perlengkapan --</option>
                                    @foreach($addOns as $addOn)
                                        <option value="{{ $addOn->id }}" data-price="{{ $addOn->price }}" data-unit="{{ $addOn->unit }}" data-name="{{ $addOn->name }}">
                                            {{ $addOn->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="addOnQty" class="block text-sm font-semibold text-gray-800 mb-2">Jumlah</label>
                                <input type="number" id="addOnQty" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" value="1" min="1">
                            </div>
                            <button type="button" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-semibold" onclick="addAddOn()">
                                <i class="fas fa-plus mr-2"></i> Tambah
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Work Notes -->
                <div class="mb-8">
                    <label for="work_notes" class="block text-sm font-semibold text-gray-800 mb-2">
                        <i class="fas fa-clipboard-list mr-2 text-orange-600"></i> Catatan Pekerjaan
                    </label>
                    <textarea id="work_notes" name="work_notes" rows="4" placeholder="Catatan tambahan tentang pekerjaan yang dilakukan, masalah yang ditemukan, dll..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">{{ old('work_notes') }}</textarea>
                </div>

                <!-- After Photos -->
                <div class="mb-8">
                    <label for="photos" class="block text-sm font-semibold text-gray-800 mb-2">
                        <i class="fas fa-camera mr-2 text-orange-600"></i> Foto AC Setelah Pekerjaan *
                    </label>

                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-orange-500 hover:bg-orange-50 transition" id="dropZone">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-700 font-semibold">Drag & drop foto di sini atau klik untuk browse</p>
                        <p class="text-xs text-gray-500 mt-1">Format: JPEG, PNG, JPG, GIF (Max 5MB per file)</p>
                        <input type="file" id="photos" name="photos[]" class="hidden" accept="image/*" multiple required>
                    </div>

                    @error('photos')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror

                    <!-- Photo Count -->
                    <div class="mt-2 text-sm text-gray-600" id="photoCount"></div>

                    <!-- Preview Gallery -->
                    <div id="preview" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-6"></div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-semibold">
                        <i class="fas fa-check-circle mr-2"></i> Simpan & Lanjut Pembayaran
                    </button>
                    <a href="{{ route('orders.show', $order) }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition text-center font-semibold">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let addOnIndex = {{ $currentAddOns->count() }};

const dropZone = document.getElementById('dropZone');
const photoInput = document.getElementById('photos');
const preview = document.getElementById('preview');
const photoCount = document.getElementById('photoCount');

// Click to upload
dropZone.addEventListener('click', () => photoInput.click());

// Drag & drop
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('bg-orange-50', 'border-orange-500');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('bg-orange-50', 'border-orange-500');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('bg-orange-50', 'border-orange-500');
    photoInput.files = e.dataTransfer.files;
    updatePhotoPreview();
});

photoInput.addEventListener('change', updatePhotoPreview);

function updatePhotoPreview() {
    preview.innerHTML = '';
    const files = Array.from(photoInput.files);

    photoCount.textContent = files.length > 0 ? `${files.length} foto dipilih` : '';

    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML = `
                <img src="${e.target.result}" alt="Foto ${index + 1}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                <div class="absolute top-1 right-1">
                    <span class="bg-orange-600 text-white px-2 py-1 rounded text-xs font-bold">${index + 1}</span>
                </div>
                <button type="button" onclick="removePhoto(${index})" class="absolute top-1 left-1 bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700 opacity-0 group-hover:opacity-100 transition">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function removePhoto(index) {
    const dt = new DataTransfer();
    const files = photoInput.files;

    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }

    photoInput.files = dt.files;
    updatePhotoPreview();
}

function addAddOn() {
    const select = document.getElementById('addOnSelect');
    const qtyInput = document.getElementById('addOnQty');
    const option = select.options[select.selectedIndex];

    if (!option.value) {
        alert('Pilih perlengkapan terlebih dahulu');
        return;
    }

    const container = document.getElementById('addOnsContainer');
    const quantity = parseInt(qtyInput.value) || 1;
    const subtotal = parseFloat(option.dataset.price) * quantity;

    const item = document.createElement('div');
    item.className = 'add-on-item bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-end gap-4';

    item.innerHTML = `
        <div class="flex-1">
            <input type="hidden" name="add_ons[${addOnIndex}][id]" value="${option.value}">
            <p class="text-sm text-gray-600 mb-1">Perlengkapan</p>
            <p class="font-semibold text-gray-900">${option.dataset.name}</p>
        </div>
        <div class="w-20">
            <p class="text-sm text-gray-600 mb-1">Qty</p>
            <input type="number" name="add_ons[${addOnIndex}][quantity]" class="w-full px-3 py-2 border border-gray-300 rounded qty-input" value="${quantity}" min="1" onchange="calculateSubtotal(this)">
        </div>
        <div class="w-32">
            <p class="text-sm text-gray-600 mb-1">Harga/Unit</p>
            <p class="font-semibold text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(parseFloat(option.dataset.price))}</p>
        </div>
        <div class="w-32">
            <p class="text-sm text-gray-600 mb-1">Subtotal</p>
            <p class="font-bold text-orange-600 subtotal-value">Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</p>
        </div>
        <button type="button" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition" onclick="this.closest('.add-on-item').remove()">
            <i class="fas fa-trash"></i>
        </button>
    `;

    container.appendChild(item);
    addOnIndex++;
    select.value = '';
    qtyInput.value = '1';
}

function calculateSubtotal(input) {
    const item = input.closest('.add-on-item');
    const priceText = item.querySelector('p:nth-of-type(4)').textContent;
    const price = parseFloat(priceText.replace(/[^\d]/g, ''));
    const qty = parseInt(input.value) || 1;
    const subtotal = price * qty;
    item.querySelector('.subtotal-value').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
}

// Set initial state for photos
updatePhotoPreview();
</script>
@endsection
