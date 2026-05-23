@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Assign Pekerja</h1>
        <p class="text-gray-600">Kelola dan assign pekerja ke pesanan yang belum di-assign</p>
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

    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Unassigned Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-tasks mr-2"></i> Pesanan Belum Di-Assign
                <span class="ml-2 inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $unassignedOrders->total() }} pesanan
                </span>
            </h2>
        </div>

        @if ($unassignedOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nomor Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Layanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tanggal Kunjungan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($unassignedOrders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-semibold text-gray-900">#{{ $order->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $order->user->phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $order->serviceType->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $order->units }} unit</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->visit_date->format('d M Y') }}</div>
                                    <div class="text-sm text-gray-600">{{ $order->visit_date->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('orders.assign-form', $order) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                        <i class="fas fa-user-plus mr-2"></i> Assign
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $unassignedOrders->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-check-circle text-4xl text-green-500 mb-4"></i>
                <p class="text-gray-600 text-lg">Semua pesanan sudah di-assign! 🎉</p>
            </div>
        @endif
    </div>

    <!-- Quick Stats -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Pesanan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Order::count() }}</p>
                </div>
                <i class="fas fa-list text-4xl text-blue-500 opacity-10"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Belum Di-Assign</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $unassignedOrders->total() }}</p>
                </div>
                <i class="fas fa-exclamation-circle text-4xl text-yellow-500 opacity-10"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Sudah Di-Assign</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Order::assigned()->count() }}</p>
                </div>
                <i class="fas fa-check-circle text-4xl text-green-500 opacity-10"></i>
            </div>
        </div>
    </div>
</div>
@endsection
