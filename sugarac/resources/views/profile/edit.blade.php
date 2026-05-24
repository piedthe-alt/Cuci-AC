@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h3 class="mb-4">Edit Profil</h3>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="name" class="form-label"><strong>Nama Lengkap *</strong></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label"><strong>Email *</strong></label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">Telepon</label>
                                    <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789">
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="city" class="form-label">Kota</label>
                                    <input type="text" id="city" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $user->city) }}" placeholder="Contoh: Jakarta">
                                    @error('city')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Masukan alamat lengkap">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="profile_picture" class="form-label">Foto Profil</label>
                            @if($user->profile_picture)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" style="max-width: 150px; max-height: 150px; border-radius: 10px;">
                                </div>
                            @endif
                            <input type="file" id="profile_picture" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*">
                            <small class="form-text text-muted d-block mt-2">Max 2MB, Format: JPEG, PNG, JPG, GIF</small>
                            @error('profile_picture')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
