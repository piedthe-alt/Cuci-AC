<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cuci AC') - Platform Layanan Cuci AC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-enter {
            animation: slideInLeft 0.3s ease-in-out;
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Top Navigation -->
    <nav class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <i class="fas fa-water text-2xl"></i>
                    <h1 class="text-xl font-bold hidden sm:block">Cuci AC Terbang</h1>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm hidden sm:inline">{{ Auth::user()->name }}</span>
                    <div class="relative group">
                        <button class="flex items-center space-x-2 focus:outline-none">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ Auth::user()->profile_picture }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover cursor-pointer">
                            @else
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-blue-600 font-bold text-sm cursor-pointer">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 pt-2 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-300 z-50">
                            <div class="bg-white text-gray-800 rounded-lg shadow-lg">
                                <a href="#profile" class="block px-4 py-2 hover:bg-gray-100 rounded-t-lg">
                                    <i class="fas fa-user mr-2"></i>Profil
                                </a>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Admin Panel
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

    <div class="flex h-screen overflow-hidden bg-gray-50">
        @if(Auth::user()->isAdmin())
            <!-- Admin Sidebar -->
            <aside class="w-64 bg-gray-900 text-white overflow-y-auto shadow-lg hidden md:block">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-6">Admin Panel</h3>

                    <nav class="space-y-2">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 transition @if(request()->routeIs('admin.dashboard')) bg-blue-600 @endif">
                            <i class="fas fa-chart-line mr-3 w-5"></i>
                            <span>Dashboard</span>
                        </a>

                        <!-- User Management -->
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 transition @if(request()->routeIs('admin.users.*')) bg-blue-600 @endif">
                            <i class="fas fa-users mr-3 w-5"></i>
                            <span>Kelola Pengguna</span>
                        </a>

                        <!-- AC Models -->
                        <a href="{{ route('admin.ac-models.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 transition @if(request()->routeIs('admin.ac-models.*')) bg-blue-600 @endif">
                            <i class="fas fa-fan mr-3 w-5"></i>
                            <span>Model AC</span>
                        </a>

                        <!-- Services (Kategori Layanan) -->
                        <a href="{{ route('admin.services.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 transition @if(request()->routeIs('admin.services.*')) bg-blue-600 @endif">
                            <i class="fas fa-folder mr-3 w-5"></i>
                            <span>Kategori Layanan</span>
                        </a>

                        <!-- Service Types -->
                        <a href="{{ route('admin.service-types.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 transition @if(request()->routeIs('admin.service-types.*')) bg-blue-600 @endif">
                            <i class="fas fa-wrench mr-3 w-5"></i>
                            <span>Jenis Layanan</span>
                        </a>

                        <!-- Staff Assignment -->
                        <a href="{{ route('orders.assignments') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 transition @if(request()->routeIs('orders.assignments')) bg-blue-600 @endif">
                            <i class="fas fa-user-check mr-3 w-5"></i>
                            <span>Assign Pekerja</span>
                        </a>

                        <hr class="my-4 border-gray-700">

                        <!-- Back to Dashboard -->
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                            <i class="fas fa-home mr-3 w-5"></i>
                            <span>Kembali ke User</span>
                        </a>
                    </nav>
                </div>
            </aside>
        @elseif(Auth::user()->isStaff())
            <!-- Staff Sidebar -->
            <aside class="w-64 bg-gray-900 text-white overflow-y-auto shadow-lg hidden md:block">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-6">Dashboard Pekerja</h3>

                    <nav class="space-y-2">
                        <!-- Dashboard -->
                        <a href="{{ route('staff.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 transition @if(request()->routeIs('staff.dashboard')) bg-blue-600 @endif">
                            <i class="fas fa-chart-line mr-3 w-5"></i>
                            <span>Dashboard</span>
                        </a>

                        <!-- My Jobs -->
                        <a href="{{ route('staff.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                            <i class="fas fa-briefcase mr-3 w-5"></i>
                            <span>Pekerjaan Saya</span>
                        </a>

                        <hr class="my-4 border-gray-700">

                        <!-- Profile -->
                        <a href="#profile" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                            <i class="fas fa-user mr-3 w-5"></i>
                            <span>Profil</span>
                        </a>
                    </nav>
                </div>
            </aside>
        @endif

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <div class="@if(Auth::user()->isAdmin()) p-4 md:p-8 @else p-4 md:p-8 @endif">
                @yield('content')
            </div>
        </main>
    </div>

    @if(Auth::user()->isAdmin())
        <script>
            // Sidebar toggle for mobile
            function toggleSidebar() {
                const sidebar = document.querySelector('aside');
                sidebar.classList.toggle('hidden');
            }
        </script>
    @endif
</body>
</html>
