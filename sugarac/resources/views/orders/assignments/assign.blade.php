@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('orders.assignments') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Assign Pekerja ke Pesanan</h1>
        <p class="text-gray-600">Pilih pekerja yang akan menangani pesanan ini</p>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i> Detail Pesanan
                </h2>

                <div class="space-y-6">
                    <!-- Order ID and Status -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-600 text-sm mb-1">Nomor Pesanan</p>
                            <p class="text-2xl font-bold text-gray-900">#{{ $order->id }}</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <p class="text-gray-600 text-sm mb-1">Status</p>
                            <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">
                            <i class="fas fa-user text-blue-600 mr-2"></i> Data Pelanggan
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div>
                                <p class="text-gray-600 text-sm">Nama</p>
                                <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Nomor Telepon</p>
                                <p class="font-medium text-gray-900">{{ $order->phone }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Alamat</p>
                                <p class="font-medium text-gray-900">{{ $order->address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Service Details -->
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">
                            <i class="fas fa-wrench text-blue-600 mr-2"></i> Detail Layanan
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-600 text-sm">Model AC</p>
                                    <p class="font-medium text-gray-900">{{ $order->acModel->name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Jenis Layanan</p>
                                    <p class="font-medium text-gray-900">{{ $order->serviceType->name }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-600 text-sm">Jumlah Unit</p>
                                    <p class="font-medium text-gray-900">{{ $order->units }} unit</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Total Harga</p>
                                    <p class="font-medium text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule -->
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">
                            <i class="fas fa-calendar text-blue-600 mr-2"></i> Jadwal
                        </h3>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-gray-600 text-sm mb-1">Tanggal dan Waktu Kunjungan</p>
                            <p class="text-lg font-bold text-gray-900">
                                {{ $order->visit_date->format('d M Y') }} - {{ $order->visit_date->format('H:i') }}
                            </p>
                        </div>
                    </div>

                    @if ($order->notes)
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-3">
                                <i class="fas fa-sticky-note text-blue-600 mr-2"></i> Catatan
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900">{{ $order->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Assign Form -->
        <div>
            <div class="bg-white rounded-lg shadow p-8 sticky top-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-user-check text-green-600 mr-2"></i> Assign Pekerja
                </h2>

                <form method="POST" action="{{ route('orders.assign-staff', $order) }}">
                    @csrf

                    <div class="space-y-6">
                        <!-- Staff Selection -->
                        <div>
                            <label for="assigned_staff_id" class="block text-sm font-semibold text-gray-700 mb-3">
                                Pilih Pekerja
                            </label>

                            @if ($staffs->count() > 0)
                                <div class="space-y-2">
                                    @foreach ($staffs as $staff)
                                        <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 transition" for="staff_{{ $staff->id }}">
                                            <input
                                                type="radio"
                                                name="assigned_staff_id"
                                                id="staff_{{ $staff->id }}"
                                                value="{{ $staff->id }}"
                                                class="w-4 h-4 text-blue-600"
                                                required
                                            >
                                            <div class="ml-3">
                                                <p class="font-medium text-gray-900">{{ $staff->name }}</p>
                                                <p class="text-sm text-gray-600">{{ $staff->phone }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                @error('assigned_staff_id')
                                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                                @enderror

                                <!-- Submit Button -->
                                <button type="submit" class="w-full mt-6 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                                    <i class="fas fa-check mr-2"></i> Assign Pekerja
                                </button>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
                                    <p class="text-sm">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        Tidak ada pekerja aktif yang tersedia. Silakan aktifkan pekerja terlebih dahulu.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Cancel Button -->
                        <a href="{{ route('orders.assignments') }}" class="block text-center px-4 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
