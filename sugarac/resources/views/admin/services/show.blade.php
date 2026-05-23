@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header & Back Button -->
    <div class="mb-8">
        <a href="{{ route('admin.services.index') }}" class="text-blue-600 hover:text-blue-900 mb-4 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $service->name }}</h1>
        <p class="text-gray-600">Lihat dan kelola semua jenis layanan dalam kategori ini</p>
    </div>

    <!-- Service Info Card -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Nama Kategori</h3>
                <p class="text-xl font-semibold text-gray-900 mt-1">{{ $service->name }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Dibuat Pada</h3>
                <p class="text-xl font-semibold text-gray-900 mt-1">{{ $service->created_at->format('d M Y H:i') }}</p>
            </div>
            @if($service->description)
                <div class="md:col-span-2">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase">Deskripsi</h3>
                    <p class="text-gray-700 mt-2">{{ $service->description }}</p>
                </div>
            @endif
        </div>
        <div class="mt-6 pt-6 border-t flex gap-2">
            <a href="{{ route('admin.services.edit', $service) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Pastikan tidak ada jenis layanan yang terikat.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-trash mr-2"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Service Types Section -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Jenis-Jenis Layanan</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $service->serviceTypes->count() }} jenis layanan tersedia</p>
            </div>
            <a href="{{ route('admin.service-types.create', ['service_id' => $service->id]) }}" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Jenis
            </a>
        </div>

        @if ($service->serviceTypes->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($service->serviceTypes as $type)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900">{{ $type->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700">{{ substr($type->description ?? '-', 0, 50) }}{{ strlen($type->description ?? '') > 50 ? '...' : '' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-green-600">Rp {{ number_format($type->price, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $type->orders()->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.service-types.show', $type) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.service-types.edit', $type) }}" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.service-types.destroy', $type) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-12 text-center">
                <i class="fas fa-inbox text-5xl text-gray-300 mb-4 block"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Jenis Layanan</h3>
                <p class="text-gray-600 mb-6">Tambahkan jenis layanan pertama untuk kategori ini.</p>
                <a href="{{ route('admin.service-types.create', ['service_id' => $service->id]) }}" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i> Tambah Jenis Layanan
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
