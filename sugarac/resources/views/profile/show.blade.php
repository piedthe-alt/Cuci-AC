@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/150" alt="{{ $user->name }}" class="rounded-circle mb-3">
                    @endif
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">{{ ucfirst($user->role) }}</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary w-100">Edit Profil</a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Informasi Profil</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Nama</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $user->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Email</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $user->email }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Telepon</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $user->phone ?? 'Tidak diisi' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Alamat</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $user->address ?? 'Tidak diisi' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Kota</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $user->city ?? 'Tidak diisi' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Status</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Profil
                        </a>
                        <a href="{{ route('profile.change-password') }}" class="btn btn-info">
                            <i class="bi bi-lock"></i> Ubah Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
