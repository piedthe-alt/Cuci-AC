@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Back Button -->
    <a href="{{ Auth::user()->role === 'staff' ? route('staff.dashboard') : route('orders.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
        <i class="fas fa-arrow-left mr-2"></i>
        {{ Auth::user()->role === 'staff' ? 'Kembali ke Dashboard' : 'Kembali ke Riwayat Pesanan' }}
    </a>

    <!-- Workflow Progress Indicator -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            @php
                $statuses = ['menunggu', 'ditugaskan', 'cek_layanan', 'pengerjaan', 'payment', 'selesai'];
                $statusLabels = [
                    'menunggu' => 'Menunggu',
                    'ditugaskan' => 'Ditugaskan',
                    'cek_layanan' => 'Cek Layanan',
                    'pengerjaan' => 'Pengerjaan',
                    'payment' => 'Pembayaran',
                    'selesai' => 'Selesai'
                ];
                $currentIndex = array_search($order->status, $statuses);
            @endphp
            @foreach($statuses as $index => $status)
                <div class="flex flex-col items-center flex-1">
                    <div class="relative w-10 h-10 rounded-full flex items-center justify-center font-bold text-white mb-2
                        @if($index < $currentIndex) bg-green-500
                        @elseif($index === $currentIndex) bg-blue-500
                        @else bg-gray-300
                        @endif">
                        @if($index < $currentIndex)
                            <i class="fas fa-check"></i>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </div>
                    <span class="text-xs text-gray-700 font-medium text-center">{{ $statusLabels[$status] }}</span>
                </div>
                @if($index < count($statuses) - 1)
                    <div class="flex-none w-1 h-1 mb-8 mx-1 @if($index < $currentIndex) bg-green-500 @else bg-gray-300 @endif" style="width: calc(100% / {{ count($statuses) }})"></div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Pesanan #{{ $order->id }}</h1>
                <p class="text-gray-600 mt-1">Dibuat: {{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="text-right">
                @switch($order->status)
                    @case('menunggu')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold bg-yellow-100 text-yellow-800">
                            <i class="fas fa-hourglass-half mr-2"></i>
                            Menunggu Penugasan
                        </span>
                        @break
                    @case('ditugaskan')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold bg-blue-100 text-blue-800">
                            <i class="fas fa-user-check mr-2"></i>
                            Ditugaskan
                        </span>
                        @break
                    @case('cek_layanan')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold bg-purple-100 text-purple-800">
                            <i class="fas fa-search mr-2"></i>
                            Cek Layanan
                        </span>
                        @break
                    @case('pengerjaan')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold bg-orange-100 text-orange-800">
                            <i class="fas fa-wrench mr-2"></i>
                            Pengerjaan
                        </span>
                        @break
                    @case('payment')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold bg-red-100 text-red-800">
                            <i class="fas fa-money-bill mr-2"></i>
                            Pembayaran
                        </span>
                        @break
                    @case('selesai')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-2"></i>
                            Selesai
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
                    <i class="fas fa-calculator mr-2"></i> Total Biaya
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Harga Dasar ({{ $order->units }} × Rp {{ number_format($order->serviceType->price, 0, ',', '.') }})</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($order->serviceType->price * $order->units, 0, ',', '.') }}</span>
                    </div>
                    @php
                        $addOnsTotal = $order->addOns->sum('subtotal');
                    @endphp
                    @if($addOnsTotal > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Add-ons</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($addOnsTotal, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="border-t pt-3">
                        <div class="flex justify-between">
                            <span class="text-lg font-bold text-gray-800">Total:</span>
                            <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff & Jadwal -->
    @if($order->assignedStaff)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Staff Info -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user-tie mr-2"></i> Pekerja yang Ditugaskan
            </h2>
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($order->assignedStaff->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $order->assignedStaff->name }}</p>
                    <p class="text-gray-600">{{ $order->assignedStaff->email }}</p>
                    <p class="text-gray-600">{{ $order->assignedStaff->phone }}</p>
                    @if($order->assigned_at)
                        <p class="text-sm text-gray-500 mt-2"><i class="fas fa-clock mr-1"></i>Ditugaskan: {{ $order->assigned_at->format('d M Y H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Jadwal Kunjungan -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-calendar-alt mr-2"></i> Jadwal Kunjungan
            </h2>
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
    @endif

    <!-- Lokasi dan Kontak -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-map-marker-alt mr-2"></i> Lokasi & Kontak
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 p-4 rounded">
                <p class="text-sm text-gray-600 mb-2">Nomor Handphone</p>
                <p class="text-lg font-bold text-gray-900">{{ $order->phone }}</p>
            </div>
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 border-l-4 border-purple-500 p-4 rounded">
                <p class="text-sm text-gray-600 mb-2">Alamat Lengkap</p>
                <p class="text-gray-900 text-sm leading-relaxed">{{ $order->address }}</p>
            </div>
        </div>
        @if ($order->latitude && $order->longitude)
            <div class="mt-4 bg-gradient-to-r from-orange-50 to-orange-100 border-l-4 border-orange-500 p-4 rounded">
                <p class="text-sm text-gray-600 mb-2">Koordinat Lokasi</p>
                <p class="text-gray-900">{{ $order->latitude }}, {{ $order->longitude }}</p>
                <a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="inline-flex items-center mt-2 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-external-link-alt mr-1"></i> Buka di Google Maps
                </a>
            </div>
        @endif
    </div>

    <!-- Before Photos -->
    @if($order->photos()->where('type', 'before')->exists())
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-camera mr-2"></i> Foto Sebelum Pekerjaan
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($order->photos()->where('type', 'before')->get() as $photo)
                <div class="relative group cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $photo->photo_path) }}')">
                    <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Foto Before" class="w-full h-48 object-cover rounded-lg">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition flex items-center justify-center">
                        <i class="fas fa-search-plus text-white text-3xl opacity-0 group-hover:opacity-100 transition"></i>
                    </div>
                </div>
            @endforeach
        </div>
        @if($order->photos()->where('type', 'before')->first()?->description)
            <div class="mt-4 bg-gray-50 p-4 rounded">
                <p class="text-sm text-gray-600 mb-1"><strong>Temuan Pengecekan:</strong></p>
                <p class="text-gray-700">{{ $order->photos()->where('type', 'before')->first()->description }}</p>
            </div>
        @endif
    </div>
    @endif

    <!-- After Photos -->
    @if($order->photos()->where('type', 'after')->exists())
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-camera mr-2"></i> Foto Setelah Pekerjaan
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($order->photos()->where('type', 'after')->get() as $photo)
                <div class="relative group cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $photo->photo_path) }}')">
                    <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Foto After" class="w-full h-48 object-cover rounded-lg">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition flex items-center justify-center">
                        <i class="fas fa-search-plus text-white text-3xl opacity-0 group-hover:opacity-100 transition"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Add-ons -->
    @if($order->addOns->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-box mr-2"></i> Perlengkapan yang Digunakan
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama Perlengkapan</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Jumlah</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Harga/Unit</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($order->addOns as $addOn)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $addOn->addOn->name }}</td>
                            <td class="px-4 py-3 text-right text-sm text-gray-900">{{ $addOn->quantity }} {{ $addOn->addOn->unit }}</td>
                            <td class="px-4 py-3 text-right text-sm text-gray-900">Rp {{ number_format($addOn->unit_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp {{ number_format($addOn->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Payment Info -->
    @if($order->payment)
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-receipt mr-2"></i> Informasi Pembayaran
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded">
                <p class="text-sm text-gray-600 mb-2">Metode Pembayaran</p>
                <p class="text-lg font-bold text-gray-900">
                    @if($order->payment->payment_method === 'cash')
                        <i class="fas fa-money-bill text-green-600 mr-2"></i>Tunai (Cash)
                    @elseif($order->payment->payment_method === 'transfer')
                        <i class="fas fa-university text-blue-600 mr-2"></i>Transfer Bank
                    @else
                        <i class="fas fa-question-circle text-gray-600 mr-2"></i>Belum Dipilih
                    @endif
                </p>
            </div>
            <div class="bg-gray-50 p-4 rounded">
                <p class="text-sm text-gray-600 mb-2">Status Pembayaran</p>
                <p class="text-lg font-bold">
                    @if($order->payment->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                            <i class="fas fa-hourglass-half mr-1"></i>Menunggu
                        </span>
                    @elseif($order->payment->status === 'confirmed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                            <i class="fas fa-check mr-1"></i>Dikonfirmasi
                        </span>
                    @elseif($order->payment->status === 'completed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Selesai
                        </span>
                    @endif
                </p>
            </div>
        </div>
        @if($order->payment->payment_method === 'transfer')
            <div class="mt-4 bg-blue-50 border border-blue-200 p-4 rounded">
                <p class="text-sm text-gray-600 mb-2"><strong>Rekening Tujuan:</strong></p>
                <p class="text-gray-900">{{ $order->payment->account_holder }}</p>
                <p class="text-gray-900">{{ $order->payment->bank_name }} - {{ $order->payment->account_number }}</p>
            </div>
        @endif
        <div class="mt-4 bg-gray-50 p-4 rounded">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Pembayaran:</span>
                <span class="text-xl font-bold text-gray-900">Rp {{ number_format($order->payment->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between mt-2">
                <span class="text-gray-600">Sudah Dibayar:</span>
                <span class="text-xl font-bold text-green-600">Rp {{ number_format($order->payment->amount_paid, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Rating -->
    @if($order->rating)
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-star mr-2"></i> Rating & Review
        </h2>
        <div class="flex items-start space-x-4">
            <div class="flex">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $order->rating->rating)
                        <i class="fas fa-star text-yellow-400 text-2xl"></i>
                    @else
                        <i class="fas fa-star text-gray-300 text-2xl"></i>
                    @endif
                @endfor
            </div>
            <div class="flex-1">
                <p class="text-lg font-bold text-gray-900">{{ $order->rating->rating }}/5 Bintang</p>
                @if($order->rating->review)
                    <p class="text-gray-700 mt-2">{{ $order->rating->review }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <a href="{{ Auth::user()->role === 'staff' ? route('staff.dashboard') : route('orders.index') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-center font-semibold">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>

        <!-- Customer Action Buttons -->
        @if(Auth::user()->role === 'user' && $order->user_id === Auth::id())
            @if($order->status === 'menunggu')
                <a href="{{ route('orders.edit', $order) }}" class="px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-center font-semibold">
                    <i class="fas fa-edit mr-2"></i> Edit Pesanan
                </a>
                <form method="POST" action="{{ route('orders.destroy', $order) }}" class="md:col-span-2" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                        <i class="fas fa-trash mr-2"></i> Batalkan Pesanan
                    </button>
                </form>
            @elseif($order->status === 'payment')
                <a href="{{ route('orders.payment-form', $order) }}" class="md:col-span-2 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center font-semibold">
                    <i class="fas fa-credit-card mr-2"></i> Lanjut Pembayaran
                </a>
            @elseif($order->status === 'selesai' && !$order->rating)
                <a href="{{ route('orders.rating-form', $order) }}" class="md:col-span-2 px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-center font-semibold">
                    <i class="fas fa-star mr-2"></i> Berikan Rating
                </a>
            @endif
        @endif

        <!-- Staff Action Buttons -->
        @if(Auth::user()->role === 'staff' && $order->assigned_staff_id === Auth::id())
            @if($order->status === 'ditugaskan')
                <a href="{{ route('orders.service-check-form', $order) }}" class="md:col-span-2 px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-center font-semibold">
                    <i class="fas fa-search mr-2"></i> Mulai Cek Layanan
                </a>
            @elseif($order->status === 'cek_layanan')
                <a href="{{ route('orders.work-progress-form', $order) }}" class="md:col-span-2 px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition text-center font-semibold">
                    <i class="fas fa-wrench mr-2"></i> Mulai Pengerjaan
                </a>
            @elseif($order->status === 'pengerjaan')
                <a href="{{ route('orders.payment-form', $order) }}" class="md:col-span-2 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-center font-semibold">
                    <i class="fas fa-money-bill mr-2"></i> Lanjut Pembayaran
                </a>
            @elseif($order->status === 'payment')
                <button type="button" class="md:col-span-2 px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-semibold" disabled>
                    <i class="fas fa-check-circle mr-2"></i> Menunggu Verifikasi Pembayaran
                </button>
            @endif
        @endif
    </div>

    <!-- Info Messages -->
    @if($order->status === 'menunggu')
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg mb-8">
            <p class="text-yellow-800">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Menunggu Penugasan:</strong> Admin akan mengassign staff dalam waktu 24 jam. Anda akan menerima notifikasi saat staff ditugaskan.
            </p>
        </div>
    @elseif($order->status === 'ditugaskan')
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-8">
            <p class="text-blue-800">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Staff Ditugaskan:</strong> Staff {{ $order->assignedStaff->name }} telah ditugaskan dan akan menuju ke lokasi Anda. Harap bersiaplah untuk menerima kedatangan staff.
            </p>
        </div>
    @elseif($order->status === 'payment')
        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg mb-8">
            <p class="text-red-800">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Menunggu Pembayaran:</strong> Pengerjaan telah selesai. Silakan lakukan pembayaran sesuai dengan metode yang telah disepakati.
            </p>
        </div>
    @endif
</div>

<!-- Image Modal -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
    <div class="max-w-4xl max-h-[90vh] flex flex-col">
        <button type="button" onclick="closeImageModal()" class="text-white hover:text-gray-300 text-3xl self-end mb-4">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="Foto" class="max-h-[80vh] object-contain">
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const img = document.getElementById('modalImage');
    img.src = imageSrc;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('imageModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
</script>
@endsection
