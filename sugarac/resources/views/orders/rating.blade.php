@extends('layouts.app')

@section('title', 'Rating & Review')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Rating & Review - {{ $order->acModel->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <strong>Pekerjaan Selesai!</strong>
                        <br><small>Berikan penilaian terhadap pelayanan staff untuk membantu kami meningkatkan kualitas layanan</small>
                    </div>

                    <!-- Order Summary -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Detail Pesanan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Pelanggan:</strong></div>
                                <div class="col-md-6">{{ $order->user->name }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6"><strong>Staff:</strong></div>
                                <div class="col-md-6">{{ $order->assignedStaff->name }}</div>
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
                                <div class="col-md-6"><strong>Total Pembayaran:</strong></div>
                                <div class="col-md-6"><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></div>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Form -->
                    <form action="{{ route('orders.submit-rating', $order) }}" method="POST">
                        @csrf

                        <div class="form-group mb-4">
                            <label for="rating" class="form-label"><strong>Rating *</strong></label>
                            <div class="rating-input">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" 
                                        {{ old('rating', $existingRating?->rating ?? 0) == $i ? 'checked' : '' }} required>
                                    <label for="rating{{ $i }}" class="star-label">
                                        <i class="bi bi-star-fill"></i>
                                    </label>
                                @endfor
                            </div>
                            <small class="form-text text-muted d-block mt-2">
                                Berikan rating dari 1 (sangat kurang puas) hingga 5 (sangat puas)
                            </small>
                            @error('rating')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="review" class="form-label"><strong>Review (Opsional)</strong></label>
                            <textarea id="review" name="review" class="form-control @error('review') is-invalid @enderror" rows="5" placeholder="Bagikan pengalaman Anda dengan layanan kami. Apa yang baik? Apa yang bisa ditingkatkan?" maxlength="1000">{{ old('review', $existingRating?->review ?? '') }}</textarea>
                            <small class="form-text text-muted d-block mt-2">Maksimal 1000 karakter</small>
                            @error('review')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Kirim Rating
                            </button>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-input {
    display: flex;
    gap: 10px;
    font-size: 2rem;
}

.rating-input input[type="radio"] {
    display: none;
}

.star-label {
    cursor: pointer;
    color: #ddd;
    transition: color 0.2s;
    margin: 0;
}

.rating-input input[type="radio"]:checked ~ .star-label,
.rating-input input[type="radio"]:checked + label,
.star-label:hover,
.rating-input input[type="radio"]:hover + label {
    color: #ffc107;
}
</style>
@endsection
