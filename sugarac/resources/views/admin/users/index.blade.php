<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 hover:opacity-80 transition">
                        <i class="fas fa-water text-3xl"></i>
                        <h1 class="text-2xl font-bold">Cuci AC - Admin</h1>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-100 transition">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Daftar Pengguna</h2>
            <p class="text-gray-600">Total pengguna: <strong>{{ $users->total() }}</strong></p>
        </div>

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

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Telepon</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Role</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($user->profile_picture)
                                        <img src="{{ $user->profile_picture }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full">
                                    @else
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-sm">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="text-gray-800">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->phone ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if($user->isAdmin()) bg-purple-100 text-purple-800
                                    @elseif($user->isStaff()) bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800
                                    @endif
                                ">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->is_active)
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-800 transition">
                                    <i class="fas fa-eye mr-1"></i>Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>Tidak ada data pengguna</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</body>
</html>
