@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Manajemen Pekerjaan</h1>
        <p class="text-gray-600">Pantau dan kelola pekerjaan yang telah di-assign ke pekerja</p>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Pekerjaan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_assigned'] }}</p>
                </div>
                <i class="fas fa-briefcase text-3xl text-blue-500 opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Menunggu Mulai</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending'] }}</p>
                </div>
                <i class="fas fa-hourglass-start text-3xl text-yellow-500 opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Sedang Dikerjakan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['confirmed'] }}</p>
                </div>
                <i class="fas fa-spinner text-3xl text-blue-600 opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Selesai</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['completed'] }}</p>
                </div>
                <i class="fas fa-check-circle text-3xl text-green-500 opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pekerja Aktif</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_staff'] }}</p>
                </div>
                <i class="fas fa-users text-3xl text-purple-500 opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow">
            <div class="flex border-b border-gray-200">
                <button class="tab-button active flex-1 px-6 py-4 font-medium text-yellow-600 border-b-2 border-yellow-600 hover:text-yellow-700 transition" data-tab="pending">
                    <i class="fas fa-hourglass-start mr-2"></i>
                    Menunggu Mulai ({{ $stats['pending'] }})
                </button>
                <button class="tab-button flex-1 px-6 py-4 font-medium text-gray-600 border-b-2 border-transparent hover:text-gray-800 hover:border-gray-300 transition" data-tab="confirmed">
                    <i class="fas fa-spinner mr-2"></i>
                    Sedang Dikerjakan ({{ $stats['confirmed'] }})
                </button>
                <button class="tab-button flex-1 px-6 py-4 font-medium text-gray-600 border-b-2 border-transparent hover:text-gray-800 hover:border-gray-300 transition" data-tab="completed">
                    <i class="fas fa-check-circle mr-2"></i>
                    Selesai ({{ $stats['completed'] }})
                </button>
            </div>
        </div>
    </div>

    <!-- Tab Content: Pending Orders -->
    <div id="pending-tab" class="tab-content mb-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-yellow-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-hourglass-start mr-2 text-yellow-600"></i>
                    Pekerjaan Menunggu Mulai
                </h2>
            </div>

            @if ($pendingOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Pekerja</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Layanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Jadwal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($pendingOrders as $order)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-gray-900">#{{ $order->id }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $order->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($order->assignedStaff)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                {{ $order->assignedStaff->name }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                Belum Di-Assign
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->serviceType->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $order->units }} unit</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->visit_date->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center px-3 py-1 rounded bg-blue-100 hover:bg-blue-200 text-blue-700 transition text-sm">
                                                <i class="fas fa-eye mr-1"></i>Lihat
                                            </a>
                                            <form action="{{ route('orders.update-status', $order) }}" method="POST" class="inline" onsubmit="return confirm('Mulai pekerjaan ini?')">
                                                @csrf
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="inline-flex items-center px-3 py-1 rounded bg-yellow-100 hover:bg-yellow-200 text-yellow-700 transition text-sm">
                                                    <i class="fas fa-play mr-1"></i>Mulai
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center bg-gray-50">
                    <i class="fas fa-check-circle text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600">Tidak ada pekerjaan yang menunggu</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Tab Content: Confirmed Orders -->
    <div id="confirmed-tab" class="tab-content mb-8 hidden">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-blue-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-spinner mr-2 text-blue-600"></i>
                    Pekerjaan Sedang Dikerjakan
                </h2>
            </div>

            @if ($confirmedOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Pekerja</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Layanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Jadwal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($confirmedOrders as $order)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-gray-900">#{{ $order->id }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $order->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($order->assignedStaff)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                {{ $order->assignedStaff->name }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->serviceType->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $order->units }} unit</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->visit_date->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center px-3 py-1 rounded bg-blue-100 hover:bg-blue-200 text-blue-700 transition text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center bg-gray-50">
                    <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600">Tidak ada pekerjaan sedang dikerjakan</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Tab Content: Completed Orders -->
    <div id="completed-tab" class="tab-content mb-8 hidden">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-green-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-check-circle mr-2 text-green-600"></i>
                    Pekerjaan Selesai
                </h2>
            </div>

            @if ($completedOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Pekerja</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Layanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Selesai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($completedOrders as $order)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-gray-900">#{{ $order->id }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $order->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($order->assignedStaff)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                {{ $order->assignedStaff->name }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->serviceType->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $order->units }} unit</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->updated_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center px-3 py-1 rounded bg-blue-100 hover:bg-blue-200 text-blue-700 transition text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center bg-gray-50">
                    <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600">Belum ada pekerjaan selesai</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Staff & Activities Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Active Staff -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-purple-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-users mr-2 text-purple-600"></i>
                    Pekerja Aktif
                </h2>
            </div>

            @if ($activeStaff->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach ($activeStaff as $staff)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $staff->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $staff->email }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-circle text-green-600 mr-1 text-xs"></i>Aktif
                                </span>
                            </div>
                            <div class="bg-gray-50 rounded p-3 text-sm">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Total Pekerjaan:</span>
                                    <span class="font-bold text-gray-900">{{ $staffStats[$staff->id]['total'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Sedang Dikerjakan:</span>
                                    <span class="font-bold text-blue-600">{{ $staffStats[$staff->id]['confirmed'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Selesai:</span>
                                    <span class="font-bold text-green-600">{{ $staffStats[$staff->id]['completed'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center bg-gray-50">
                    <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-600">Tidak ada pekerja aktif</p>
                </div>
            @endif
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-indigo-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-history mr-2 text-indigo-600"></i>
                    Aktivitas Terbaru
                </h2>
            </div>

            @if ($recentActivities->count() > 0)
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                    @foreach ($recentActivities as $order)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 pt-1">
                                    @if ($order->status === 'pending')
                                        <i class="fas fa-hourglass-start text-yellow-500"></i>
                                    @elseif ($order->status === 'confirmed')
                                        <i class="fas fa-spinner text-blue-500"></i>
                                    @elseif ($order->status === 'completed')
                                        <i class="fas fa-check-circle text-green-500"></i>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">Pesanan #{{ $order->id }} - {{ $order->user->name }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Layanan: {{ $order->serviceType->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if ($order->status === 'pending')
                                            Menunggu untuk dimulai
                                        @elseif ($order->status === 'confirmed')
                                            Sedang dikerjakan oleh {{ $order->assignedStaff->name ?? 'Tidak ada pekerja' }}
                                        @elseif ($order->status === 'completed')
                                            Selesai pada {{ $order->updated_at->format('d/m/Y H:i') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    @if ($order->status === 'pending')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>
                                    @elseif ($order->status === 'confirmed')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Proses</span>
                                    @elseif ($order->status === 'completed')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center bg-gray-50">
                    <i class="fas fa-history text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-600">Belum ada aktivitas</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', function() {
        const tabName = this.getAttribute('data-tab');

        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        document.getElementById(tabName + '-tab').classList.remove('hidden');

        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('text-yellow-600', 'border-yellow-600');
            btn.classList.add('text-gray-600', 'border-transparent');
        });

        this.classList.remove('text-gray-600', 'border-transparent');
        this.classList.add('text-yellow-600', 'border-yellow-600');
    });
});
</script>

<style>
.tab-content {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
@endsection
