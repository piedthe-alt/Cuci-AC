@extends('layouts.app')

@section('title', 'Owner Dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3 class="mb-4">Dashboard Owner - Laporan Keuangan</h3>

    <!-- Main KPI Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Revenue</h6>
                    <h2 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                    <small>Semua waktu</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Revenue Bulan Ini</h6>
                    <h2 class="mb-0">Rp {{ number_format($thisMonthRevenue, 0, ',', '.') }}</h2>
                    <small>{{ now()->format('F Y') }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Pesanan</h6>
                    <h2 class="mb-0">{{ $totalOrders }}</h2>
                    <small>Semua pesanan</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h6 class="card-title">Pesanan Selesai</h6>
                    <h2 class="mb-0">{{ $completedOrders }}</h2>
                    <small>{{ round(($completedOrders / max($totalOrders, 1)) * 100) }}% dari total</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Chart -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Tren Revenue (6 Bulan Terakhir)</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Average Order Value:</strong><br>
                        Rp {{ number_format($totalRevenue / max($completedOrders, 1), 0, ',', '.') }}
                    </div>
                    <hr>
                    <div>
                        <strong>Total Staff:</strong><br>
                        {{ $staffPerformance->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Performance -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Kinerja Staff</h6>
                    <a href="{{ route('owner.staff-ratings') }}" class="btn btn-sm btn-primary">Lihat Detail Rating</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Staff</th>
                                    <th>Penugasan</th>
                                    <th>Selesai</th>
                                    <th>% Selesai</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staffPerformance as $staff)
                                    <tr>
                                        <td><strong>{{ $staff['name'] }}</strong></td>
                                        <td>{{ $staff['assigned_orders'] }}</td>
                                        <td>{{ $staff['completed_orders'] }}</td>
                                        <td>
                                            @php
                                                $percentage = $staff['assigned_orders'] > 0 ? round(($staff['completed_orders'] / $staff['assigned_orders']) * 100) : 0;
                                            @endphp
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" style="width: {{ $percentage }}%">
                                                    {{ $percentage }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($staff['avg_rating'] > 0)
                                                <div class="text-warning">
                                                    {{ number_format($staff['avg_rating'], 1) }}/5
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
                                            @else
                                                <span class="text-muted">Belum ada rating</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Tidak ada staff</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
            datasets: [{
                label: 'Revenue (Rp)',
                data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.3,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: '#0d6efd'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
