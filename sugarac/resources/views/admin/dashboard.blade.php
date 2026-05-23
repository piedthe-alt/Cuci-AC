<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cuci AC</title>
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
                    <h1 class="text-2xl font-bold">Cuci AC - Admin</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm">{{ Auth::user()->name }} (Admin)</span>
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

                        <div class="absolute right-0 pt-2 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-300 z-10">
                            <div class="bg-white text-gray-800 rounded-lg shadow-lg">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-t-lg">
                                    <i class="fas fa-home mr-2"></i>Dashboard User
                                </a>
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

        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Alert Unassigned Orders -->
        @if ($unassignedCount > 0)
            <div class="mb-8 bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-400 text-yellow-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-3xl text-yellow-600 mr-4 mt-1"></i>
                        <div>
                            <h3 class="text-2xl font-bold mb-2">
                                <i class="fas fa-tasks mr-2"></i>
                                {{ $unassignedCount }} Pesanan Menunggu untuk Di-Assign
                            </h3>
                            <p class="text-yellow-700">Ada {{ $unassignedCount }} pesanan pelanggan yang masih belum di-assign ke pekerja. Segera lakukan assignment agar pekerja dapat menangani pesanan tersebut.</p>
                        </div>
                    </div>
                    <a href="{{ route('orders.assignments') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-bold transition whitespace-nowrap ml-4">
                        <i class="fas fa-arrow-right mr-2"></i>Assign Sekarang
                    </a>
                </div>

                @if ($recentUnassignedOrders->count() > 0)
                    <div class="mt-6 border-t-2 border-yellow-300 pt-6">
                        <h4 class="font-bold mb-3 text-lg">Pesanan Terbaru yang Belum Di-Assign:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($recentUnassignedOrders as $order)
                                <div class="bg-white rounded-lg p-4 border border-yellow-200">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="font-bold text-gray-900">#{{ $order->id }} - {{ $order->user->name }}</span>
                                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">{{ $order->serviceType->name }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $order->visit_date->format('d M Y H:i') }}
                                    </p>
                                    <a href="{{ route('orders.assign-form', $order) }}" class="text-sm bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded inline-block transition">
                                        <i class="fas fa-user-plus mr-1"></i>Assign
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="mb-8 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 text-green-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-3xl text-green-600 mr-4"></i>
                    <div>
                        <h3 class="text-xl font-bold mb-1">✅ Semua Pesanan Sudah Di-Assign!</h3>
                        <p class="text-green-700">Tidak ada pesanan yang menunggu untuk di-assign. Semua pesanan sudah dikelola dengan baik.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-3xl font-bold mb-2">Admin Dashboard</h2>
            <p class="text-blue-100">Kelola semua aspek sistem Cuci AC dari sini</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Pengguna</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Admins -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Admin</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalAdmins }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-shield-alt text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Staff -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Staff</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalStaff }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-briefcase text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Regular Users -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total User Regular</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalRegularUsers }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-user text-yellow-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- User Management -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-users text-blue-600 mr-3"></i>Manajemen Pengguna
                </h3>
                <p class="text-gray-600 mb-4">Kelola semua pengguna sistem Cuci AC</p>
                <a href="{{ route('admin.users.index') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-list mr-2"></i>Lihat Semua Pengguna
                </a>
            </div>

            <!-- AC Models Management -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-fan text-green-600 mr-3"></i>Manajemen Model AC
                </h3>
                <p class="text-gray-600 mb-4">Kelola jenis-jenis model AC yang tersedia</p>
                <div class="flex gap-2">
                    <a href="{{ route('admin.ac-models.create') }}" class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Model
                    </a>
                    <a href="{{ route('admin.ac-models.index') }}" class="inline-block bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                        <i class="fas fa-list mr-2"></i>Lihat Semua
                    </a>
                </div>
            </div>

            <!-- Service Types Management -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-wrench text-purple-600 mr-3"></i>Manajemen Jenis Layanan
                </h3>
                <p class="text-gray-600 mb-4">Kelola jenis-jenis layanan cuci AC</p>
                <div class="flex gap-2">
                    <a href="{{ route('admin.service-types.create') }}" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Layanan
                    </a>
                    <a href="{{ route('admin.service-types.index') }}" class="inline-block bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                        <i class="fas fa-list mr-2"></i>Lihat Semua
                    </a>
                </div>
            </div>

            <!-- Staff Assignment Management -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-check text-indigo-600 mr-3"></i>Assign Pekerja ke Pesanan
                </h3>
                <p class="text-gray-600 mb-4">Assign pekerja secara manual ke pesanan pelanggan</p>
                <a href="{{ route('orders.assignments') }}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-tasks mr-2"></i>Kelola Assignment
                </a>
            </div>

            <!-- Work Management -->
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg shadow-lg p-6 border-2 border-yellow-400">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-hammer text-orange-600 mr-3"></i>Manajemen Pekerjaan
                </h3>
                <p class="text-gray-600 mb-4">Pantau dan kelola pekerjaan yang telah di-assign ke pekerja</p>
                <a href="{{ route('admin.work-management') }}" class="inline-block bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:from-yellow-600 hover:to-orange-600 transition font-semibold">
                    <i class="fas fa-play-circle mr-2"></i>Mulai Kelola Pekerjaan
                </a>
            </div>

            <!-- System Info -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-indigo-600 mr-3"></i>Informasi Sistem
                </h3>
                <ul class="space-y-2 text-gray-600">
                    <li><strong>Aplikasi:</strong> Cuci AC v1.0</li>
                    <li><strong>Environment:</strong> {{ config('app.env') }}</li>
                    <li><strong>Debug Mode:</strong> {{ config('app.debug') ? 'On' : 'Off' }}</li>
                    <li><strong>Database:</strong> {{ config('database.default') }}</li>
                </ul>
            </div>
        </div>

        <!-- Data Tables Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            <!-- AC Models Quick View -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-fan text-green-600 mr-2"></i>Model AC Terbaru
                </h3>
                @php
                    $acModels = \App\Models\AcModel::latest()->limit(5)->get();
                @endphp
                @if($acModels->count() > 0)
                    <div class="space-y-3">
                        @foreach($acModels as $model)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $model->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $model->description ? substr($model->description, 0, 50) . '...' : 'Tanpa deskripsi' }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.ac-models.edit', $model) }}" class="text-yellow-600 hover:text-yellow-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.ac-models.show', $model) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('admin.ac-models.index') }}" class="inline-block mt-4 text-green-600 hover:text-green-900 font-semibold">
                        Lihat semua Model AC →
                    </a>
                @else
                    <p class="text-gray-600">Belum ada model AC. <a href="{{ route('admin.ac-models.create') }}" class="text-green-600 hover:text-green-900 font-semibold">Tambah sekarang</a></p>
                @endif
            </div>

            <!-- Service Types Quick View -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-wrench text-purple-600 mr-2"></i>Jenis Layanan Terbaru
                </h3>
                @php
                    $serviceTypes = \App\Models\ServiceType::latest()->limit(5)->get();
                @endphp
                @if($serviceTypes->count() > 0)
                    <div class="space-y-3">
                        @foreach($serviceTypes as $service)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $service->name }}</p>
                                    <p class="text-sm text-gray-600">Harga: <span class="font-semibold">Rp {{ number_format($service->price, 0, ',', '.') }}</span></p>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.service-types.edit', $service) }}" class="text-yellow-600 hover:text-yellow-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.service-types.show', $service) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('admin.service-types.index') }}" class="inline-block mt-4 text-purple-600 hover:text-purple-900 font-semibold">
                        Lihat semua Jenis Layanan →
                    </a>
                @else
                    <p class="text-gray-600">Belum ada jenis layanan. <a href="{{ route('admin.service-types.create') }}" class="text-purple-600 hover:text-purple-900 font-semibold">Tambah sekarang</a></p>
                @endif
            </div>
    </div>
</body>
</html>
