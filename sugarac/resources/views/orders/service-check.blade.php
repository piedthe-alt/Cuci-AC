@extends('layouts.app')

@section('title', 'Cek Layanan')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Cek Layanan - {{ $order->acModel->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Status Pesanan:</strong> Cek Layanan
                        <br><small>Sebelum melakukan pekerjaan, lakukan pengecekan pada AC dan upload foto kondisi sebelum pekerjaan</small>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Pelanggan:</strong> {{ $order->user->name }}</p>
                            <p><strong>Telepon:</strong> {{ $order->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Alamat:</strong> {{ $order->address }}</p>
                            <p><strong>Tanggal Kunjungan:</strong> {{ $order->visit_date->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <form action="{{ route('orders.submit-service-check', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="findings" class="form-label"><strong>Hasil Pengecekan *</strong></label>
                            <textarea id="findings" name="findings" class="form-control @error('findings') is-invalid @enderror" rows="4" placeholder="Masukan hasil pengecekan AC. Contoh: Freon habis, kondensor kotor, dll" required>{{ old('findings') }}</textarea>
                            @error('findings')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="photos" class="form-label"><strong>Upload Foto Before (Sebelum Pekerjaan) *</strong></label>
                            <div class="input-group">
                                <input type="file" id="photos" name="photos[]" class="form-control @error('photos') is-invalid @enderror" accept="image/*" multiple required>
                            </div>
                            <small class="form-text text-muted d-block mt-2">
                                - Format: JPEG, PNG, JPG, GIF (Max 5MB per file)<br>
                                - Ambil foto dari berbagai sudut untuk dokumentasi
                            </small>
                            @error('photos')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror

                            <!-- Preview area -->
                            <div id="preview" class="mt-3"></div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Simpan & Lanjut ke Pengerjaan
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
