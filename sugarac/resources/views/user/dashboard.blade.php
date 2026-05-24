@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Pesanan</h1>
        <p class="text-gray-600">Pantau status pesanan cuci AC Anda</p>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Tombol Buat Pesanan -->
    <div class="mb-8">
        <a href="{{ route('orders.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>
            Buat Pesanan Baru
        </a>
    </div>

    <!-- Pesanan Sedang Berjalan -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Pesanan Sedang Berjalan</h2>
        
        @if ($ongoingOrders->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-6">
                @foreach ($ongoingOrders as $order)
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Pesanan #{{ $order->id }}</h3>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $order->getStatusBadgeClass() }}">
                                {{ $order->getStatusLabel() }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Model AC</p>
                                <p class="font-semibold text-gray-800">{{ $order->acModel->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Layanan</p>
                                <p class="font-semibold text-gray-800">{{ $order->serviceType->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Jumlah AC</p>
                                <p class="font-semibold text-gray-800">{{ $order->units }} unit</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Tanggal Kunjungan</p>
                                <p class="font-semibold text-gray-800">{{ $order->visit_date->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="text-xs text-gray-500 uppercase mb-1">Alamat</p>
                            <p class="text-sm text-gray-800">{{ $order->address }}</p>
                        </div>

                        @if ($order->assignedStaff)
                            <div class="mb-4 p-3 bg-blue-50 rounded">
                                <p class="text-xs text-gray-500 uppercase mb-1">Staff Terugaskan</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $order->assignedStaff->name }}</p>
                            </div>
                        @endif

                        <!-- Status Flow Timeline -->
                        <div class="mb-4">
                            <div class="text-xs text-gray-500 uppercase mb-2">Progress Status</div>
                            <div class="flex items-center gap-2 text-xs">
                                <div class="flex-1 text-center">
                                    <div class="rounded-full w-6 h-6 mx-auto mb-1 {{ $order->status !== null ? 'bg-blue-500 text-white' : 'bg-gray-200' }} flex items-center justify-center">
                                        <i class="fas fa-check text-xs"></i>
                                    </div>
                                    <p class="text-gray-600">Menunggu</p>
                                </div>
                                <div class="flex-1 text-center">
                                    <div class="rounded-full w-6 h-6 mx-auto mb-1 {{ in_array($order->status, ['ditugaskan', 'cek_layanan', 'pengerjaan', 'payment', 'selesai']) ? 'bg-blue-500 text-white' : 'bg-gray-200' }} flex items-center justify-center">
                                        <i class="fas fa-check text-xs"></i>
                                    </div>
                                    <p class="text-gray-600">Tugas</p>
                                </div>
                                <div class="flex-1 text-center">
                                    <div class="rounded-full w-6 h-6 mx-auto mb-1 {{ in_array($order->status, ['pengerjaan', 'payment', 'selesai']) ? 'bg-blue-500 text-white' : 'bg-gray-200' }} flex items-center justify-center">
                                        <i class="fas fa-check text-xs"></i>
                                    </div>
                                    <p class="text-gray-600">Kerja</p>
                                </div>
                                <div class="flex-1 text-center">
                                    <div class="rounded-full w-6 h-6 mx-auto mb-1 {{ in_array($order->status, ['payment', 'selesai']) ? 'bg-blue-500 text-white' : 'bg-gray-200' }} flex items-center justify-center">
                                        <i class="fas fa-check text-xs"></i>
                                    </div>
                                    <p class="text-gray-600">Bayar</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <a href="{{ route('orders.show', $order->id) }}" class="flex-1 px-4 py-2 bg-gray-100 text-gray-800 rounded hover:bg-gray-200 transition text-center text-sm font-medium">
                                Lihat Detail
                            </a>
                            @if ($order->status === 'payment')
                                <a href="{{ route('orders.payment-form', $order->id) }}" class="flex-1 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-center text-sm font-medium">
                                    Bayar
                                </a>
                            @elseif ($order->status === 'payment_completed')
                                <a href="{{ route('orders.rating-form', $order->id) }}" class="flex-1 px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition text-center text-sm font-medium">
                                    Beri Rating
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-600">Belum ada pesanan yang sedang berjalan</p>
            </div>
        @endif
    </div>

    <!-- Riwayat Pesanan -->
    <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Riwayat Pesanan</h2>
        
        @if ($completedOrders->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model AC</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Layanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($completedOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->acModel->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->serviceType->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->assignedStaff->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($order->rating)
                                        <div class="flex items-center">
                                            @for ($i = 0; $i < $order->rating->rating; $i++)
                                                <i class="fas fa-star text-yellow-400"></i>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-600">({{ $order->rating->rating }})</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $completedOrders->links() }}
            </div>
        @else
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <i class="fas fa-history text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-600">Belum ada riwayat pesanan</p>
            </div>
        @endif
    </div>
</div>
@endsection
