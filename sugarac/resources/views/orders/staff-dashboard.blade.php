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
                                    @if ($order->status === 'pending')
                                        <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            <i class="fas fa-hourglass-half mr-1"></i> Pending
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
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm"
                                        onclick="openDetailModal({{ $order->id }})">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </button>
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

<!-- Detail Modal -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Detail Pesanan</h2>
            <button type="button" onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <div id="modalContent" class="p-6">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
function openDetailModal(orderId) {
    const modal = document.getElementById('detailModal');
    const modalContent = document.getElementById('modalContent');

    // Find the row
    const table = document.querySelector('table');
    const rows = table.querySelectorAll('tbody tr');
    let orderData = null;

    rows.forEach(row => {
        if (row.querySelector('td:first-child').textContent.includes(orderId)) {
            const cells = row.querySelectorAll('td');
            orderData = {
                id: orderId,
                customer: cells[1].textContent,
                service: cells[2].textContent,
                address: cells[3].textContent,
                schedule: cells[4].textContent,
                status: cells[5].textContent
            };
        }
    });

    if (orderData) {
        // Build modal content from table data
        const html = `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm">ID Pesanan</p>
                        <p class="font-bold text-lg">#${orderData.id}</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded">
                        <p class="text-gray-600 text-sm">Status</p>
                        <p class="font-semibold">${orderData.status}</p>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Data Pelanggan</h3>
                    <div class="space-y-2">
                        ${orderData.customer}
                    </div>
                </div>

                <div class="border-t pt-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Layanan</h3>
                    <div class="space-y-2">
                        ${orderData.service}
                    </div>
                </div>

                <div class="border-t pt-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Lokasi</h3>
                    <p class="text-gray-700">${orderData.address}</p>
                </div>

                <div class="border-t pt-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Jadwal Kunjungan</h3>
                    <p class="text-gray-700">${orderData.schedule}</p>
                </div>

                <form method="POST" action="/orders/${orderId}/status" class="border-t pt-4">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''}">
                    <label class="block mb-3">
                        <span class="text-sm font-semibold text-gray-800">Perbarui Status</span>
                        <select name="status" class="w-full mt-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="ditugaskan">Ditugaskan</option>
                            <option value="cek_layanan">Cek Layanan</option>
                            <option value="pengerjaan">Pengerjaan</option>
                            <option value="payment">Pembayaran</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </label>
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </form>
            </div>
        `;
        modalContent.innerHTML = html;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('detailModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailModal();
    }
});
</script>
@endsection
