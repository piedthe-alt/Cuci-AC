@extends('layouts.app')

@section('title', 'Profil User')

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
                    <p class="text-muted">Pelanggan</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary w-100">Edit Profil</a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Pesanan</h6>
                            <h2 class="mb-0">{{ $totalOrders }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Pesanan Selesai</h6>
                            <h2 class="mb-0">{{ $completedOrders }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6 class="card-title">Pesanan Aktif</h6>
                            <h2 class="mb-0">{{ $ongoingOrders }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Informasi Pribadi</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Nama</strong></div>
                        <div class="col-md-8">{{ $user->name }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Email</strong></div>
                        <div class="col-md-8">{{ $user->email }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Telepon</strong></div>
                        <div class="col-md-8">{{ $user->phone ?? 'Tidak diisi' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Alamat</strong></div>
                        <div class="col-md-8">{{ $user->address ?? 'Tidak diisi' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Kota</strong></div>
                        <div class="col-md-8">{{ $user->city ?? 'Tidak diisi' }}</div>
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
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-list-check"></i><br>Pesanan Saya
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('orders.create') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-plus-circle"></i><br>Buat Pesanan
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
