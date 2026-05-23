@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Back Button -->
    <a href="{{ route('admin.service-types.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Layanan
    </a>

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $serviceType->name }}</h1>
                <p class="text-gray-600 mt-2">Dibuat: {{ $serviceType->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600 mb-1">Harga per Unit</p>
                <p class="text-3xl font-bold text-green-600">Rp {{ number_format($serviceType->price, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Service Info -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle mr-2"></i> Informasi Layanan
            </h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Nama Layanan</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $serviceType->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Deskripsi</p>
                    <p class="text-gray-900">{{ $serviceType->description ?? 'Tidak ada deskripsi' }}</p>
                </div>
                <div class="bg-green-50 rounded p-4 mt-4">
                    <p class="text-sm text-gray-600 mb-1">Harga per Unit</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($serviceType->price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-bar mr-2"></i> Statistik
            </h2>
            <div class="space-y-4">
                <div class="bg-white rounded p-4">
                    <p class="text-sm text-gray-600 mb-1">Total Pesanan</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $serviceType->orders()->count() }}</p>
                </div>
                <div class="bg-white rounded p-4">
                    <p class="text-sm text-gray-600 mb-1">Pesanan Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $serviceType->orders()->where('status', 'pending')->count() }}</p>
                </div>
                <div class="bg-white rounded p-4">
                    <p class="text-sm text-gray-600 mb-1">Pesanan Selesai</p>
                    <p class="text-2xl font-bold text-green-600">{{ $serviceType->orders()->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-list mr-2"></i> Pesanan Terbaru
        </h2>

        @if ($serviceType->orders()->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Model AC</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Units</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($serviceType->orders()->latest()->limit(10)->get() as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium">#{{ $order->id }}</td>
                                <td class="px-4 py-3 text-sm">{{ $order->user->name }}</td>
                                <td class="px-4 py-3 text-sm">{{ $order->acModel->name }}</td>
                                <td class="px-4 py-3 text-sm">{{ $order->units }}</td>
                                <td class="px-4 py-3 text-sm font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">Pending</span>
                                            @break
                                        @case('confirmed')
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">Confirmed</span>
                                            @break
                                        @case('completed')
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">Completed</span>
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600 text-center py-8">Belum ada pesanan untuk layanan ini</p>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-4">
        <a href="{{ route('admin.service-types.index') }}" class="flex-1 px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-center font-semibold">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
        <a href="{{ route('admin.service-types.edit', $serviceType) }}" class="flex-1 px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-center font-semibold">
            <i class="fas fa-edit mr-2"></i> Edit
        </a>
        <form method="POST" action="{{ route('admin.service-types.destroy', $serviceType) }}" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                <i class="fas fa-trash mr-2"></i> Hapus
            </button>
        </form>
    </div>
</div>
@endsection
