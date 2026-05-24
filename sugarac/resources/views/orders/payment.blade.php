@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Pembayaran - {{ $order->acModel->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <strong>Status Pesanan:</strong> Menunggu Pembayaran
                    </div>

                    <!-- Order Summary -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Ringkasan Pesanan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Pelanggan:</strong></div>
                                <div class="col-md-6">{{ $order->user->name }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Model AC:</strong></div>
                                <div class="col-md-6">{{ $order->acModel->name }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Layanan:</strong></div>
                                <div class="col-md-6">{{ $order->serviceType->name }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Jumlah Unit:</strong></div>
                                <div class="col-md-6">{{ $order->units }}</div>
                            </div>
                            <hr>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Harga Layanan:</strong></div>
                                <div class="col-md-6">Rp {{ number_format($order->serviceType->price * $order->units, 0, ',', '.') }}</div>
                            </div>

                            @if($order->addOns->count() > 0)
                                <div class="row mb-2">
                                    <div class="col-md-6"><strong>Add-ons:</strong></div>
                                    <div class="col-md-6"></div>
                                </div>
                                @foreach($order->addOns as $addOn)
                                    <div class="row mb-1 ps-3">
                                        <div class="col-md-6">{{ $addOn->addOn->name }} ({{ $addOn->quantity }} x Rp {{ number_format($addOn->unit_price, 0, ',', '.') }})</div>
                                        <div class="col-md-6">Rp {{ number_format($addOn->subtotal, 0, ',', '.') }}</div>
                                    </div>
                                @endforeach
                            @endif

                            <hr>
                            <div class="row">
                                <div class="col-md-6"><h5>Total Pembayaran:</h5></div>
                                <div class="col-md-6"><h5 class="text-danger">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</h5></div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <form action="{{ route('orders.submit-payment', $order) }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="payment_method" class="form-label"><strong>Metode Pembayaran *</strong></label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="payment_method" id="payment_cash" value="cash" required>
                                <label class="btn btn-outline-primary" for="payment_cash">
                                    <i class="bi bi-cash-coin"></i> Tunai (Cash)
                                </label>

                                <input type="radio" class="btn-check" name="payment_method" id="payment_transfer" value="transfer" required>
                                <label class="btn btn-outline-primary" for="payment_transfer">
                                    <i class="bi bi-bank"></i> Transfer Bank
                                </label>
                            </div>
                            @error('payment_method')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Transfer Details (Hidden by default) -->
                        <div id="transferDetails" class="card mb-4" style="display: none;">
                            <div class="card-body">
                                <h6 class="mb-3">Detail Transfer Bank</h6>
                                
                                <div class="form-group mb-3">
                                    <label for="bank_name" class="form-label"><strong>Nama Bank *</strong></label>
                                    <input type="text" id="bank_name" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" placeholder="Contoh: BCA, Mandiri, dll" value="{{ old('bank_name') }}">
                                    @error('bank_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="account_number" class="form-label"><strong>Nomor Rekening *</strong></label>
                                    <input type="text" id="account_number" name="account_number" class="form-control @error('account_number') is-invalid @enderror" placeholder="Nomor rekening" value="{{ old('account_number') }}">
                                    @error('account_number')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="account_holder" class="form-label"><strong>Nama Pemilik Rekening *</strong></label>
                                    <input type="text" id="account_holder" name="account_holder" class="form-control @error('account_holder') is-invalid @enderror" placeholder="Nama pemilik rekening" value="{{ old('account_holder') }}">
                                    @error('account_holder')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="amount_paid" class="form-label"><strong>Jumlah yang Dibayarkan (Rp) *</strong></label>
                            <input type="number" id="amount_paid" name="amount_paid" class="form-control @error('amount_paid') is-invalid @enderror" value="{{ old('amount_paid', $payment->total_amount) }}" min="0" step="1000" required>
                            @error('amount_paid')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="payment_notes" class="form-label">Catatan Pembayaran</label>
                            <textarea id="payment_notes" name="payment_notes" class="form-control" rows="3" placeholder="Catatan tambahan tentang pembayaran">{{ old('payment_notes') }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Konfirmasi Pembayaran
                            </button>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('payment_cash').addEventListener('change', function() {
    document.getElementById('transferDetails').style.display = 'none';
    document.getElementById('bank_name').removeAttribute('required');
    document.getElementById('account_number').removeAttribute('required');
    document.getElementById('account_holder').removeAttribute('required');
});

document.getElementById('payment_transfer').addEventListener('change', function() {
    document.getElementById('transferDetails').style.display = 'block';
    document.getElementById('bank_name').setAttribute('required', 'required');
    document.getElementById('account_number').setAttribute('required', 'required');
    document.getElementById('account_holder').setAttribute('required', 'required');
});
</script>
@endsection
