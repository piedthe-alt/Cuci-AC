@extends('layouts.app')

@section('title', 'Profil Staff')

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
                    <p class="text-muted">Staff Technician</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary w-100">Edit Profil</a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Penugasan</h6>
                            <h2 class="mb-0">{{ $assignedOrders }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Pekerjaan Selesai</h6>
                            <h2 class="mb-0">{{ $completedOrders }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-warning">
                        <div class="card-body">
                            <h6 class="card-title">Rating Rata-rata</h6>
                            <div>
                                <h3>{{ number_format($avgRating, 1) }}/5</h3>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($avgRating))
                                            <i class="bi bi-star-fill"></i>
                                        @elseif($i - 0.5 <= $avgRating)
                                            <i class="bi bi-star-half"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">({{ $totalRatings }} ratings)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Informasi Kontak</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Telepon:</strong> {{ $user->phone ?? 'Tidak diisi' }}</p>
                            <p><strong>Alamat:</strong> {{ $user->address ?? 'Tidak diisi' }}</p>
                            <p><strong>Status:</strong> <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">{{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Menu Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-clipboard-list"></i><br>Dashboard Penugasan
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-list-check"></i><br>Riwayat Pekerjaan
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-warning w-100">
                                <i class="bi bi-person"></i><br>Edit Profil
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('profile.change-password') }}" class="btn btn-outline-danger w-100">
                                <i class="bi bi-lock"></i><br>Ubah Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
