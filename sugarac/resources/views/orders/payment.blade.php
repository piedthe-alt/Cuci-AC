@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container mx-auto px-6 py-8">
    <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Pesanan
    </a>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-money-bill text-red-600 mr-2"></i> Pembayaran
                </h1>
                <p class="text-gray-600">{{ $order->acModel->name }} - {{ $order->serviceType->name }}</p>
            </div>

            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-6">
                <p class="text-red-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Instruksi:</strong> Pekerjaan telah selesai. Silakan lakukan pembayaran sesuai dengan metode yang telah disepakati. Admin akan memverifikasi pembayaran Anda.
                </p>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-receipt mr-2"></i> Ringkasan Pesanan
                </h2>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pelanggan:</span>
                        <span class="font-semibold text-gray-900">{{ $order->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Model AC:</span>
                        <span class="font-semibold text-gray-900">{{ $order->acModel->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Layanan:</span>
                        <span class="font-semibold text-gray-900">{{ $order->serviceType->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah Unit:</span>
                        <span class="font-semibold text-gray-900">{{ $order->units }} Unit</span>
                    </div>
                </div>

                <div class="border-t border-gray-300 pt-4 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Harga Layanan:</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($order->serviceType->price * $order->units, 0, ',', '.') }}</span>
                    </div>

                    @if($order->addOns->count() > 0)
                        <div class="text-gray-600 font-semibold mt-3">Add-ons:</div>
                        @foreach($order->addOns as $addOn)
                            <div class="flex justify-between pl-4">
                                <span class="text-gray-600">{{ $addOn->addOn->name }} ({{ $addOn->quantity }} × Rp {{ number_format($addOn->unit_price, 0, ',', '.') }})</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($addOn->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="border-t border-gray-300 pt-4 mt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-800">Total Pembayaran:</span>
                        <span class="text-3xl font-bold text-red-600">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <form action="{{ route('orders.submit-payment', $order) }}" method="POST">
                @csrf

                <!-- Payment Method -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-800 mb-4">
                        <i class="fas fa-credit-card text-red-600 mr-2"></i> Metode Pembayaran *
                    </label>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Cash Option -->
                        <label class="relative">
                            <input type="radio" name="payment_method" value="cash" class="hidden peer" onchange="toggleTransferDetails()">
                            <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 transition">
                                <div class="flex items-start">
                                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-green-500 peer-checked:bg-green-500 mt-1 mr-3"></div>
                                    <div>
                                        <p class="font-semibold text-gray-900"><i class="fas fa-money-bill text-green-600 mr-2"></i> Tunai (Cash)</p>
                                        <p class="text-sm text-gray-600 mt-1">Pembayaran langsung kepada staff</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Transfer Option -->
                        <label class="relative">
                            <input type="radio" name="payment_method" value="transfer" class="hidden peer" onchange="toggleTransferDetails()">
                            <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 transition">
                                <div class="flex items-start">
                                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500 mt-1 mr-3"></div>
                                    <div>
                                        <p class="font-semibold text-gray-900"><i class="fas fa-university text-blue-600 mr-2"></i> Transfer Bank</p>
                                        <p class="text-sm text-gray-600 mt-1">Transfer ke rekening yang disediakan</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>

                    @error('payment_method')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Transfer Details (Hidden by default) -->
                <div id="transferDetails" class="hidden mb-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Detail Transfer Bank</h3>

                    <div class="mb-4">
                        <label for="bank_name" class="block text-sm font-semibold text-gray-800 mb-2">Nama Bank *</label>
                        <input type="text" id="bank_name" name="bank_name" placeholder="Contoh: BCA, Mandiri, BNI, etc"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('bank_name') border-red-500 @enderror"
                            value="{{ old('bank_name') }}">
                        @error('bank_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="account_number" class="block text-sm font-semibold text-gray-800 mb-2">Nomor Rekening *</label>
                        <input type="text" id="account_number" name="account_number" placeholder="Nomor rekening tujuan"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('account_number') border-red-500 @enderror"
                            value="{{ old('account_number') }}">
                        @error('account_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="account_holder" class="block text-sm font-semibold text-gray-800 mb-2">Nama Pemilik Rekening *</label>
                        <input type="text" id="account_holder" name="account_holder" placeholder="Nama pemilik rekening"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('account_holder') border-red-500 @enderror"
                            value="{{ old('account_holder') }}">
                        @error('account_holder')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Amount Paid -->
                <div class="mb-8">
                    <label for="amount_paid" class="block text-sm font-semibold text-gray-800 mb-2">
                        <i class="fas fa-money-bill text-red-600 mr-2"></i> Jumlah yang Dibayarkan (Rp) *
                    </label>
                    <input type="number" id="amount_paid" name="amount_paid"
                        class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('amount_paid') border-red-500 @enderror"
                        value="{{ old('amount_paid', $payment->total_amount) }}" min="0" step="1000" required>
                    <p class="text-sm text-gray-600 mt-2">Total yang harus dibayar: <strong>Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</strong></p>
                    @error('amount_paid')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Notes -->
                <div class="mb-8">
                    <label for="payment_notes" class="block text-sm font-semibold text-gray-800 mb-2">
                        <i class="fas fa-clipboard mr-2 text-red-600"></i> Catatan Pembayaran
                    </label>
                    <textarea id="payment_notes" name="payment_notes" rows="3" placeholder="Catatan tambahan tentang pembayaran (opsional)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">{{ old('payment_notes') }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                        <i class="fas fa-check-circle mr-2"></i> Konfirmasi Pembayaran
                    </button>
                    <a href="{{ route('orders.show', $order) }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition text-center font-semibold">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                </div>
            </form>

            <!-- Info Box -->
            <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                <p class="text-yellow-800 text-sm">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Catatan:</strong> Setelah melakukan pembayaran, admin akan memverifikasi dan mengubah status pesanan ke selesai. Anda dapat memberikan rating setelah pembayaran diverifikasi.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function toggleTransferDetails() {
    const cashMethod = document.querySelector('input[name="payment_method"][value="cash"]').checked;
    const transferDetails = document.getElementById('transferDetails');
    const bankNameInput = document.getElementById('bank_name');
    const accountNumberInput = document.getElementById('account_number');
    const accountHolderInput = document.getElementById('account_holder');

    if (cashMethod) {
        transferDetails.classList.add('hidden');
        bankNameInput.removeAttribute('required');
        accountNumberInput.removeAttribute('required');
        accountHolderInput.removeAttribute('required');
    } else {
        transferDetails.classList.remove('hidden');
        bankNameInput.setAttribute('required', 'required');
        accountNumberInput.setAttribute('required', 'required');
        accountHolderInput.setAttribute('required', 'required');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
    if (selectedMethod && selectedMethod.value === 'transfer') {
        toggleTransferDetails();
    }
});
</script>
@endsection
