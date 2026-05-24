@extends('layouts.app')

@section('title', 'Cek Layanan')

@section('content')
<div class="container mx-auto px-6 py-8">
    <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Pesanan
    </a>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-search text-purple-600 mr-2"></i> Cek Layanan AC
                </h1>
                <p class="text-gray-600">{{ $order->acModel->name }} - {{ $order->serviceType->name }}</p>
            </div>

            <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded mb-6">
                <p class="text-purple-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Instruksi:</strong> Sebelum melakukan pekerjaan, lakukan pengecekan menyeluruh pada AC dan upload minimal satu foto kondisi AC sebelum pekerjaan dimulai.
                </p>
            </div>

            <!-- Order Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 bg-gray-50 p-6 rounded-lg">
                <div>
                    <p class="text-sm text-gray-600">Pelanggan</p>
                    <p class="font-bold text-gray-900">{{ $order->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Telepon</p>
                    <p class="font-bold text-gray-900">{{ $order->phone }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600">Alamat</p>
                    <p class="font-bold text-gray-900">{{ $order->address }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Kunjungan</p>
                    <p class="font-bold text-gray-900">{{ $order->visit_date->format('d M Y H:i') }}</p>
                </div>
            </div>

            <form action="{{ route('orders.submit-service-check', $order) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Findings -->
                <div class="mb-6">
                    <label for="findings" class="block text-sm font-semibold text-gray-800 mb-2">
                        <i class="fas fa-clipboard-list mr-2 text-purple-600"></i> Hasil Pengecekan *
                    </label>
                    <textarea id="findings" name="findings" rows="4" placeholder="Contoh: Freon habis, kondensor kotor, filter kotor, sirip bengkok, dll"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('findings') border-red-500 @enderror" required>{{ old('findings') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Deskripsi singkat tentang kondisi AC yang Anda cek</p>
                    @error('findings')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Photo Upload -->
                <div class="mb-6">
                    <label for="photos" class="block text-sm font-semibold text-gray-800 mb-2">
                        <i class="fas fa-camera mr-2 text-purple-600"></i> Foto AC Sebelum Pekerjaan *
                    </label>

                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition" id="dropZone">
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
                    <button type="submit" class="flex-1 px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-semibold">
                        <i class="fas fa-check-circle mr-2"></i> Simpan & Lanjut Pengerjaan
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
const dropZone = document.getElementById('dropZone');
const photoInput = document.getElementById('photos');
const preview = document.getElementById('preview');
const photoCount = document.getElementById('photoCount');

// Click to upload
dropZone.addEventListener('click', () => photoInput.click());

// Drag & drop
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('bg-purple-50', 'border-purple-500');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('bg-purple-50', 'border-purple-500');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('bg-purple-50', 'border-purple-500');
    photoInput.files = e.dataTransfer.files;
    updatePreview();
});

photoInput.addEventListener('change', updatePreview);

function updatePreview() {
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
                    <span class="bg-purple-600 text-white px-2 py-1 rounded text-xs font-bold">${index + 1}</span>
                </div>
                <button type="button" onclick="removeFile(${index})" class="absolute top-1 left-1 bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700 opacity-0 group-hover:opacity-100 transition">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function removeFile(index) {
    const dt = new DataTransfer();
    const files = photoInput.files;

    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }

    photoInput.files = dt.files;
    updatePreview();
}

// Set initial state
updatePreview();
</script>
@endsection
