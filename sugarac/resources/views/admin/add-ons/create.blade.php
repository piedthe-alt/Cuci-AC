@extends('layouts.app')

@section('title', 'Tambah Add-on')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h3 class="mb-4">Tambah Add-on Baru</h3>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.add-ons.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name" class="form-label"><strong>Nama Add-on *</strong></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Pipa AC, Freon, Kapasitor, dll" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Deskripsi singkat tentang add-on">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price" class="form-label"><strong>Harga (Rp) *</strong></label>
                                    <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" placeholder="0" value="{{ old('price') }}" min="0" step="1000" required>
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="unit" class="form-label"><strong>Satuan *</strong></label>
                                    <input type="text" id="unit" name="unit" class="form-control @error('unit') is-invalid @enderror" placeholder="Contoh: pcs, liter, meter, roll" value="{{ old('unit') }}" required>
                                    @error('unit')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="stock" class="form-label">Stok</label>
                            <input type="number" id="stock" name="stock" class="form-control @error('stock') is-invalid @enderror" placeholder="0" value="{{ old('stock', 0) }}" min="0">
                            @error('stock')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : 'checked' }}>
                            <label class="form-check-label" for="is_active">
                                Aktif (Add-on dapat digunakan di pesanan)
                            </label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                            <a href="{{ route('admin.add-ons.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
