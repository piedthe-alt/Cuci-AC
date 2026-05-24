@extends('layouts.app')

@section('title', 'Manajemen Add-ons')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Manajemen Add-ons (Perlengkapan)</h3>
        <a href="{{ route('admin.add-ons.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Add-on
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($addOns as $addOn)
                            <tr>
                                <td>
                                    <strong>{{ $addOn->name }}</strong>
                                    <br><small class="text-muted">{{ $addOn->description }}</small>
                                </td>
                                <td>Rp {{ number_format($addOn->price, 0, ',', '.') }}</td>
                                <td>{{ $addOn->unit }}</td>
                                <td>
                                    <span class="badge bg-{{ $addOn->stock > 0 ? 'success' : 'danger' }}">
                                        {{ $addOn->stock ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $addOn->is_active ? 'success' : 'secondary' }}">
                                        {{ $addOn->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.add-ons.edit', $addOn) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.add-ons.destroy', $addOn) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada add-on. <a href="{{ route('admin.add-ons.create') }}">Tambah sekarang</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $addOns->links() }}
        </div>
    </div>
</div>
@endsection
