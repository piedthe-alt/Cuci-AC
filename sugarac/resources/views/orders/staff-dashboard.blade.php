@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Pekerja</h1>
        <p class="text-gray-600">Kelola pekerjaan yang telah di-assign untuk Anda</p>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Assigned -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Pekerjaan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_assigned'] }}</p>
                </div>
                <i class="fas fa-briefcase text-4xl text-blue-500 opacity-10"></i>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pending</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending'] }}</p>
                </div>
                <i class="fas fa-hourglass text-4xl text-yellow-500 opacity-10"></i>
            </div>
        </div>

        <!-- Confirmed -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Dikonfirmasi</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['confirmed'] }}</p>
                </div>
                <i class="fas fa-check-square text-4xl text-blue-600 opacity-10"></i>
            </div>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Selesai</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['selesai'] }}</p>
                </div>
                <i class="fas fa-check-circle text-4xl text-green-500 opacity-10"></i>
            </div>
        </div>
    </div>

    <!-- Assigned Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-list mr-2"></i> Daftar Pekerjaan
            </h2>
        </div>

        @if ($assignedOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Layanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jadwal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($assignedOrders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold text-gray-900">#{{ $order->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $order->phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->serviceType->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $order->units }} unit {{ $order->acModel->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                                        {{ Str::limit($order->address, 30) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->visit_date->format('d M Y') }}</div>
                                    <div class="text-sm text-gray-600">{{ $order->visit_date->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($order->status === 'ditugaskan')
                                        <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            <i class="fas fa-user-check mr-1"></i> Ditugaskan
                                        </span>
                                    @elseif ($order->status === 'cek_layanan')
                                        <span class="inline-block bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            <i class="fas fa-search mr-1"></i> Cek Layanan
                                        </span>
                                    @elseif ($order->status === 'pengerjaan')
                                        <span class="inline-block bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            <i class="fas fa-wrench mr-1"></i> Pengerjaan
                                        </span>
                                    @elseif ($order->status === 'payment')
                                        <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            <i class="fas fa-money-bill mr-1"></i> Pembayaran
                                        </span>
                                    @elseif ($order->status === 'selesai')
                                        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            <i class="fas fa-check-circle mr-1"></i> Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($order->status === 'ditugaskan')
                                        <a href="{{ route('orders.service-check-form', $order) }}" class="inline-flex items-center px-3 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition text-sm">
                                            <i class="fas fa-search mr-1"></i> Cek Layanan
                                        </a>
                                    @elseif($order->status === 'cek_layanan')
                                        <a href="{{ route('orders.work-progress-form', $order) }}" class="inline-flex items-center px-3 py-2 bg-orange-600 text-white rounded hover:bg-orange-700 transition text-sm">
                                            <i class="fas fa-wrench mr-1"></i> Mulai Kerja
                                        </a>
                                    @elseif($order->status === 'pengerjaan')
                                        <a href="{{ route('orders.payment-form', $order) }}" class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm">
                                            <i class="fas fa-money-bill mr-1"></i> Pembayaran
                                        </a>
                                    @else
                                        <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm">
                                            <i class="fas fa-eye mr-1"></i> Lihat Detail
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $assignedOrders->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg">Belum ada pekerjaan yang di-assign untuk Anda</p>
                <p class="text-gray-500 text-sm mt-2">Tunggu admin untuk mengassign pekerjaan</p>
            </div>
        @endif
    </div>
</div>

@endsection
