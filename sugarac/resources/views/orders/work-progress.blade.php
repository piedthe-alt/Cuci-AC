@extends('layouts.app')

@section('title', 'Pengerjaan AC')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Pengerjaan AC - {{ $order->acModel->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Status Pesanan:</strong> Pengerjaan
                        <br><small>Lakukan pengerjaan, input perlengkapan yang digunakan, dan upload foto setelah pekerjaan selesai</small>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Pelanggan:</strong> {{ $order->user->name }}</p>
                            <p><strong>Model AC:</strong> {{ $order->acModel->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Layanan:</strong> {{ $order->serviceType->name }}</p>
                            <p><strong>Jumlah Unit:</strong> {{ $order->units }}</p>
                        </div>
                    </div>

                    <form action="{{ route('orders.submit-work-progress', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Add-ons Section -->
                        <div class="mb-4">
                            <h6 class="mb-3"><strong>Perlengkapan yang Digunakan</strong></h6>
                            <div id="addOnsContainer">
                                @if($currentAddOns->count() > 0)
                                    @foreach($currentAddOns as $orderAddOn)
                                        <div class="add-on-item card p-3 mb-2">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="hidden" name="add_ons[{{ $loop->index }}][id]" value="{{ $orderAddOn->addOn->id }}">
                                                    <label>{{ $orderAddOn->addOn->name }}</label>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="number" name="add_ons[{{ $loop->index }}][quantity]" class="form-control" value="{{ $orderAddOn->quantity }}" placeholder="Qty" min="1">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" value="Rp {{ number_format($orderAddOn->addOn->price, 0, ',', '.') }}/{{ $orderAddOn->addOn->unit }}" disabled>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" value="Rp {{ number_format($orderAddOn->subtotal, 0, ',', '.') }}" disabled>
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.add-on-item').remove()">Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="addOnSelect" class="form-label">Tambah Perlengkapan</label>
                                <select id="addOnSelect" class="form-select">
                                    <option value="">-- Pilih Perlengkapan --</option>
                                    @foreach($addOns as $addOn)
                                        <option value="{{ $addOn->id }}" data-price="{{ $addOn->price }}" data-unit="{{ $addOn->unit }}" data-name="{{ $addOn->name }}">
                                            {{ $addOn->name }} (Rp {{ number_format($addOn->price, 0, ',', '.') }}/{{ $addOn->unit }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addAddOn()">Tambah</button>
                            </div>
                        </div>

                        <hr>

                        <!-- Notes -->
                        <div class="form-group mb-3">
                            <label for="work_notes" class="form-label"><strong>Catatan Pekerjaan</strong></label>
                            <textarea id="work_notes" name="work_notes" class="form-control" rows="3" placeholder="Catatan tambahan tentang pekerjaan yang dilakukan">{{ old('work_notes') }}</textarea>
                        </div>

                        <!-- After Photos -->
                        <div class="form-group mb-4">
                            <label for="photos" class="form-label"><strong>Upload Foto After (Setelah Pekerjaan) *</strong></label>
                            <div class="input-group">
                                <input type="file" id="photos" name="photos[]" class="form-control @error('photos') is-invalid @enderror" accept="image/*" multiple required>
                            </div>
                            <small class="form-text text-muted d-block mt-2">
                                - Format: JPEG, PNG, JPG, GIF (Max 5MB per file)<br>
                                - Ambil foto dari berbagai sudut untuk dokumentasi hasil pekerjaan
                            </small>
                            @error('photos')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror

                            <!-- Preview area -->
                            <div id="preview" class="mt-3"></div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Simpan & Lanjut ke Pembayaran
                            </button>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let addOnIndex = {{ $currentAddOns->count() }};

function addAddOn() {
    const select = document.getElementById('addOnSelect');
    const option = select.options[select.selectedIndex];
    
    if (!option.value) {
        alert('Pilih perlengkapan terlebih dahulu');
        return;
    }

    const container = document.getElementById('addOnsContainer');
    const item = document.createElement('div');
    item.className = 'add-on-item card p-3 mb-2';
    
    item.innerHTML = `
        <div class="row">
            <div class="col-md-3">
                <input type="hidden" name="add_ons[${addOnIndex}][id]" value="${option.value}">
                <label>${option.dataset.name}</label>
            </div>
            <div class="col-md-2">
                <input type="number" name="add_ons[${addOnIndex}][quantity]" class="form-control qty-input" value="1" placeholder="Qty" min="1" onchange="calculateSubtotal(this)">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" value="Rp ${new Intl.NumberFormat('id-ID').format(option.dataset.price)}/${option.dataset.unit}" disabled>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control subtotal-input" value="Rp ${new Intl.NumberFormat('id-ID').format(option.dataset.price)}" disabled>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.add-on-item').remove()">Hapus</button>
            </div>
        </div>
    `;
    
    container.appendChild(item);
    addOnIndex++;
    select.value = '';
}

function calculateSubtotal(input) {
    const item = input.closest('.add-on-item');
    const price = parseFloat(item.querySelector('input[type="text"]').value.replace(/[^\d]/g, ''));
    const qty = parseInt(input.value) || 0;
    const subtotal = price * qty;
    item.querySelector('.subtotal-input').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
}

document.getElementById('photos').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    
    Array.from(this.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '150px';
            img.style.maxHeight = '150px';
            img.className = 'rounded m-2 border';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endsection
