@extends('layouts.app')

@section('title', 'Rating Staff')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Rating & Kinerja Staff</h3>
        <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="row">
        @forelse($staffRatings as $staff)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">{{ $staff['name'] }}</h6>
                            <div class="text-warning">
                                <h5 class="mb-0">{{ $staff['avg_rating'] }}/5</h5>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($staff['avg_rating']))
                                        <i class="bi bi-star-fill"></i>
                                    @elseif($i - 0.5 <= $staff['avg_rating'])
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <small class="text-muted">{{ $staff['total_ratings'] }} ratings</small>
                    </div>
                    <div class="card-body">
                        @if($staff['ratings']->count() > 0)
                            <div class="rating-list" style="max-height: 400px; overflow-y: auto;">
                                @foreach($staff['ratings'] as $rating)
                                    <div class="mb-3 pb-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <strong>{{ $rating->user->name }}</strong>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $rating->rating)
                                                        <i class="bi bi-star-fill"></i>
                                                    @else
                                                        <i class="bi bi-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mb-1">
                                            {{ $rating->order->visit_date->format('d/m/Y') }}
                                        </small>
                                        @if($rating->review)
                                            <p class="mb-0" style="font-size: 0.9rem;">{{ $rating->review }}</p>
                                        @else
                                            <p class="mb-0 text-muted" style="font-size: 0.9rem;">Tidak ada review</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">Belum ada rating</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <div class="alert alert-info">
                    Tidak ada data staff
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
