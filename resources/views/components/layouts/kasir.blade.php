<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Kasir - Warung Madura POS' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        .nav-link {
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            transform: translateY(-1px);
        }
    </style>
    @livewireStyles
</head>

<body class="bg-slate-50 antialiased">
    <!-- Navbar Kasir -->
    <nav class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & Nama -->
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-9 h-9 bg-slate-900 rounded-lg">
                        <i class="fas fa-utensils text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900 leading-tight">Warung Madura</p>
                        <p class="text-xs text-slate-500 leading-tight">Panel Kasir</p>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('kasir.kasir-dashboard') }}"
                        class="nav-link flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium
                               {{ request()->routeIs('kasir.kasir-dashboard') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        <i
                            class="fas fa-home {{ request()->routeIs('kasir.kasir-dashboard') ? 'text-white' : 'text-slate-500' }}"></i>
                        <span>Beranda</span>
                    </a>
                    <a href="{{ route('kasir.pos') }}"
                        class="nav-link flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium
                               {{ request()->routeIs('kasir.pos') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        <i
                            class="fas fa-cash-register {{ request()->routeIs('pos.index') ? 'text-white' : 'text-slate-500' }}"></i>
                        <span>Kasir / POS</span>
                    </a>
                </div>

                <!-- User Info & Logout -->
                <div class="flex items-center space-x-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-slate-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <div class="w-8 h-8 bg-slate-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-slate-600 text-xs"></i>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="nav-link flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main>
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>