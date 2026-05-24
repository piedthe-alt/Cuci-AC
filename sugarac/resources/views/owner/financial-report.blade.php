@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Laporan Keuangan Detail</h3>
        <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Revenue Summary -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Ringkasan Total Revenue</h6>
                </div>
                <div class="card-body">
                    <h3 class="text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <small class="text-muted">Dari {{ $dailyRevenue->count() }} hari</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue by Service Type -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Revenue Berdasarkan Jenis Layanan</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Layanan</th>
                                    <th>Jumlah Pesanan</th>
                                    <th>Total Revenue</th>
                                    <th>Rata-rata</th>
                                    <th>% dari Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalFromServices = $revenueByServiceType->sum('total');
                                @endphp
                                @forelse($revenueByServiceType as $service)
                                    <tr>
                                        <td><strong>{{ $service['service'] }}</strong></td>
                                        <td>{{ $service['count'] }}</td>
                                        <td>Rp {{ number_format($service['total'], 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($service['average'], 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $percentage = $totalFromServices > 0 ? round(($service['total'] / $totalFromServices) * 100) : 0;
                                            @endphp
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" style="width: {{ $percentage }}%; background-color: {{ ['#0d6efd', '#198754', '#ffc107', '#fd7e14', '#dc3545'][$loop->index % 5] }}">
                                                    {{ $percentage }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Pie Chart</h6>
                </div>
                <div class="card-body">
                    <canvas id="serviceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Revenue -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Revenue Harian (30 Hari Terakhir)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Revenue</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dailyRevenue as $day)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y (l)') }}</td>
                                        <td><strong>Rp {{ number_format($day->total, 0, ',', '.') }}</strong></td>
                                        <td>
                                            @php
                                                $percentage = $totalRevenue > 0 ? round(($day->total / $totalRevenue) * 100) : 0;
                                            @endphp
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-info" style="width: {{ $percentage }}%">
                                                    {{ $percentage }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Tidak ada data</td>
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
    const ctx = document.getElementById('serviceChart').getContext('2d');
    const colors = ['#0d6efd', '#198754', '#ffc107', '#fd7e14', '#dc3545', '#6f42c1', '#20c997'];
    
    const serviceChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($revenueByServiceType->pluck('service')) !!},
            datasets: [{
                data: {!! json_encode($revenueByServiceType->pluck('total')) !!},
                backgroundColor: colors.slice(0, {!! count($revenueByServiceType) !!})
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
