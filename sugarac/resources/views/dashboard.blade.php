<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cuci AC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-water text-3xl"></i>
                    <h1 class="text-2xl font-bold">Cuci AC</h1>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm">{{ Auth::user()->name }}</span>
                    <div class="relative group">
                        <button class="flex items-center space-x-2 focus:outline-none">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ Auth::user()->profile_picture }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full cursor-pointer">
                            @else
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-blue-600 font-bold cursor-pointer">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 pt-2 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-300 z-10">
                            <div class="bg-white text-gray-800 rounded-lg shadow-lg">
                                <a href="#profile" class="block px-4 py-2 hover:bg-gray-100 rounded-t-lg">
                                    <i class="fas fa-user mr-2"></i>Profil
                                </a>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-2"></i>Admin Panel
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded-b-lg border-t">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-8">
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="text-blue-100">Peran: <span class="font-semibold capitalize">{{ Auth::user()->role }}</span></p>
            @if(Auth::user()->google_id)
                <p class="text-blue-100 mt-2"><i class="fab fa-google mr-2"></i>Akun terhubung dengan Google</p>
            @endif
        </div>

        <!-- User Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Email Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-envelope text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Email</p>
                        <p class="text-gray-800 font-semibold">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Phone Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-phone text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Telepon</p>
                        <p class="text-gray-800 font-semibold">{{ Auth::user()->phone ?? 'Belum diisi' }}</p>
                    </div>
                </div>
            </div>

            <!-- City Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-map-marker-alt text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Kota</p>
                        <p class="text-gray-800 font-semibold">{{ Auth::user()->city ?? 'Belum diisi' }}</p>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Status</p>
                        <p class="text-gray-800 font-semibold">{{ Auth::user()->is_active ? 'Aktif' : 'Tidak Aktif' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Info -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Informasi Profil Lengkap</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-600 font-semibold">Nama Lengkap</label>
                        <p class="text-lg text-gray-800 mt-1">{{ Auth::user()->name }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600 font-semibold">Email</label>
                        <p class="text-lg text-gray-800 mt-1">{{ Auth::user()->email }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600 font-semibold">Nomor Telepon</label>
                        <p class="text-lg text-gray-800 mt-1">{{ Auth::user()->phone ?? 'Belum diisi' }}</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-600 font-semibold">Alamat</label>
                        <p class="text-lg text-gray-800 mt-1">{{ Auth::user()->address ?? 'Belum diisi' }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600 font-semibold">Kota</label>
                        <p class="text-lg text-gray-800 mt-1">{{ Auth::user()->city ?? 'Belum diisi' }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600 font-semibold">Peran</label>
                        <p class="text-lg text-gray-800 mt-1">
                            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold capitalize">
                                {{ Auth::user()->role }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Edit Button -->
            <div class="mt-8 flex justify-end">
                <button class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-edit mr-2"></i>Edit Profil
                </button>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="mt-12">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Pesanan Cuci AC</h3>
                <a href="{{ route('orders.create') }}" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-plus mr-2"></i>Buat Pesanan
                </a>
            </div>

            @php
                $recentOrders = Auth::user()->orders()->latest()->limit(5)->get();
            @endphp

            @if ($recentOrders->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-800">ID Pesanan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-800">Model AC</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-800">Layanan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-800">Tanggal Kunjungan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-800">Total Harga</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-800">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-800">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($recentOrders as $order)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-gray-900">#{{ $order->id }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-700">{{ $order->acModel->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-700">{{ $order->serviceType->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-700">{{ $order->visit_date->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>Menunggu
                                                </span>
                                                @break
                                            @case('confirmed')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    <i class="fas fa-check mr-1"></i>Dikonfirmasi
                                                </span>
                                                @break
                                            @case('completed')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>Selesai
                                                </span>
                                                @break
                                            @case('cancelled')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                    <i class="fas fa-times mr-1"></i>Dibatalkan
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 font-semibold">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- View All Orders Button -->
                <div class="mt-4 text-center">
                    <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-900 font-semibold">
                        Lihat Semua Pesanan <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <i class="fas fa-box text-5xl text-gray-300 mb-4 block"></i>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Pesanan</h4>
                    <p class="text-gray-600 mb-6">Anda belum membuat pesanan cuci AC. Mulai dengan membuat pesanan baru sekarang.</p>
                    <a href="{{ route('orders.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:shadow-lg transition">
                        <i class="fas fa-plus mr-2"></i>Buat Pesanan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
