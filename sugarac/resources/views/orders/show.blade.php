@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Back Button -->
    <a href="{{ route('orders.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat Pesanan
    </a>

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Pesanan #{{ $order->id }}</h1>
                <p class="text-gray-600 mt-1">Dibuat: {{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="text-right">
                @switch($order->status)
                    @case('pending')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-2"></i>
                            Menunggu Konfirmasi
                        </span>
                        @break
                    @case('confirmed')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold bg-blue-100 text-blue-800">
                            <i class="fas fa-check-circle mr-2"></i>
                            Dikonfirmasi
                        </span>
                        @break
                    @case('completed')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold bg-green-100 text-green-800">
                            <i class="fas fa-check-double mr-2"></i>
                            Selesai
                        </span>
                        @break
                    @case('cancelled')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold bg-red-100 text-red-800">
                            <i class="fas fa-times mr-2"></i>
                            Dibatalkan
                        </span>
                        @break
                @endswitch
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Informasi Layanan -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-info-circle mr-2"></i> Informasi Layanan
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Model AC:</span>
                        <span class="font-semibold text-gray-900">{{ $order->acModel->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jenis Layanan:</span>
                        <span class="font-semibold text-gray-900">{{ $order->serviceType->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah Unit:</span>
                        <span class="font-semibold text-gray-900">{{ $order->units }} Unit</span>
                    </div>
                    <div class="flex justify-between border-t pt-3">
                        <span class="font-semibold text-gray-800">Harga per Unit:</span>
                        <span class="text-lg font-bold text-blue-600">Rp {{ number_format($order->serviceType->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Estimasi Biaya -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-calculator mr-2"></i> Estimasi Biaya
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Harga Dasar ({{ $order->units }} × Rp {{ number_format($order->serviceType->price, 0, ',', '.') }})</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($order->serviceType->price * $order->units, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t pt-3">
                        <div class="flex justify-between">
                            <span class="text-lg font-bold text-gray-800">Total Harga:</span>
                            <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal dan Lokasi -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Jadwal Kunjungan -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-calendar-alt mr-2"></i> Jadwal Kunjungan
            </h2>
            <div class="space-y-4">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-4 rounded">
                    <div class="flex items-start">
                        <i class="fas fa-calendar text-blue-600 text-2xl mr-4 mt-1"></i>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal dan Waktu</p>
                            <p class="text-lg font-bold text-gray-900">{{ $order->visit_date->format('l, d M Y - H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lokasi dan Kontak -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-map-marker-alt mr-2"></i> Lokasi & Kontak
            </h2>
            <div class="space-y-4">
                <div class="bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-sm text-gray-600 mb-2">Nomor Handphone</p>
                    <p class="text-lg font-bold text-gray-900">{{ $order->phone }}</p>
                </div>
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 border-l-4 border-purple-500 p-4 rounded">
                    <p class="text-sm text-gray-600 mb-2">Alamat Lengkap</p>
                    <p class="text-gray-900 leading-relaxed">{{ $order->address }}</p>
                </div>
                @if ($order->latitude && $order->longitude)
                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 border-l-4 border-orange-500 p-4 rounded">
                        <p class="text-sm text-gray-600 mb-2">Koordinat Lokasi</p>
                        <p class="text-gray-900">{{ $order->latitude }}, {{ $order->longitude }}</p>
                        <a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="inline-flex items-center mt-2 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-external-link-alt mr-1"></i> Buka di Google Maps
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Catatan -->
    @if ($order->notes)
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-sticky-note mr-2"></i> Catatan Tambahan
            </h2>
            <p class="text-gray-700 bg-gray-50 p-4 rounded">{{ $order->notes }}</p>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex gap-4 mb-8">
        <a href="{{ route('orders.index') }}" class="flex-1 px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-center font-semibold">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
        @if ($order->status === 'pending')
            <a href="{{ route('orders.edit', $order) }}" class="flex-1 px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-center font-semibold">
                <i class="fas fa-edit mr-2"></i> Edit Pesanan
            </a>
            <form method="POST" action="{{ route('orders.destroy', $order) }}" class="flex-1" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                    <i class="fas fa-trash mr-2"></i> Batalkan Pesanan
                </button>
            </form>
        @endif
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
        <p class="text-gray-700">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            <strong>Informasi:</strong> Admin akan mengkonfirmasi pesanan Anda dalam waktu 24 jam. Anda akan menerima notifikasi melalui email dan SMS ke nomor handphone yang terdaftar.
        </p>
    </div>
</div>
@endsection
