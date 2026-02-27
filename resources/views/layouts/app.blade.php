<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair+display:700|figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased bg-slate-50">
    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-white border-r border-pink-100 flex-shrink-0 hidden md:flex flex-col shadow-sm">
            <div class="p-6 border-b border-pink-50">
                <h1 class="text-2xl font-playfair font-bold text-pink-600 tracking-tighter">GLOWUP</h1>
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] font-bold mt-1">Admin Panel</p>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-4 space-y-1">
                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest px-3 py-2">Menu Utama</div>

                <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('dashboard') }}"
                   class="flex items-center p-3 text-sm font-semibold rounded-2xl transition-all {{ request()->routeIs('admin.dashboard') || request()->routeIs('dashboard') ? 'bg-pink-600 text-white shadow-lg' : 'text-gray-600 hover:bg-pink-50' }}">
                    <span class="mr-3 text-lg">📊</span> Dashboard
                </a>

                @if(Auth::user()->role == 'admin')
                    <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest px-3 py-2 mt-6">Manajemen Toko</div>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center p-3 text-sm font-semibold rounded-2xl transition-all {{ request()->routeIs('admin.products.*') ? 'bg-pink-600 text-white shadow-lg' : 'text-gray-600 hover:bg-pink-50' }}">
                        <span class="mr-3 text-lg">🧴</span> Product
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center p-3 text-sm font-semibold rounded-2xl transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-pink-600 text-white shadow-lg' : 'text-gray-600 hover:bg-pink-50' }}">
                        <span class="mr-3 text-lg">🏷️</span> Kategori
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 text-sm font-semibold rounded-2xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-pink-600 text-white shadow-lg' : 'text-gray-600 hover:bg-pink-50' }}">
                        <span class="mr-3 text-lg">👥</span> Kelola User
                    </a>

                    <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest px-3 py-2 mt-6">Laporan</div>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center p-3 text-sm font-semibold rounded-2xl transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-pink-600 text-white shadow-lg' : 'text-gray-600 hover:bg-pink-50' }}">
                        <span class="mr-3 text-lg">🛍️</span> Pesanan Masuk
                    </a>
                    <a href="{{ route('admin.laporan.index') }}" class="flex items-center p-3 text-sm font-semibold rounded-2xl transition-all {{ request()->routeIs('admin.laporan.index') ? 'bg-pink-600 text-white shadow-lg' : 'text-gray-600 hover:bg-pink-50' }}">
                        <span class="mr-3 text-lg">💰</span> Laporan Keuangan
                    </a>
                @else
                    <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest px-3 py-2 mt-6">Belanja</div>
                    <a href="{{ route('customer.katalog') }}" class="flex items-center p-3 text-sm font-semibold rounded-2xl transition-all {{ request()->routeIs('customer.katalog') ? 'bg-pink-600 text-white shadow-lg' : 'text-gray-600 hover:bg-pink-50' }}">
                        <span class="mr-3 text-lg">🛍️</span> Katalog Produk
                    </a>
                    <a href="{{ route('cart.index') }}" class="flex items-center p-3 text-sm font-semibold rounded-2xl transition-all {{ request()->routeIs('cart.index') ? 'bg-pink-600 text-white shadow-lg' : 'text-gray-600 hover:bg-pink-50' }}">
                        <span class="mr-3 text-lg">🛒</span> Keranjang Saya
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex items-center p-3 text-sm font-semibold rounded-2xl transition-all {{ request()->routeIs('orders.*') ? 'bg-pink-600 text-white shadow-lg' : 'text-gray-600 hover:bg-pink-50' }}">
                        <span class="mr-3 text-lg">📜</span> Riwayat Pesanan
                    </a>
                @endif
            </nav>

            <div class="p-4 border-t border-pink-50 bg-pink-50/30">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-pink-600 flex items-center justify-center text-white text-xs font-bold uppercase">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-pink-600 font-medium uppercase tracking-tighter">{{ Auth::user()->role }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white border-b border-pink-50">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil! ✨',
                text: "{{ session('success') }}",
                timer: 2500,
                showConfirmButton: false,
                customClass: { popup: 'rounded-[2rem] shadow-xl', title: 'font-playfair text-pink-600' }
            });
        @endif
    </script>

    {{-- Script Grafik akan muncul di sini --}}
    @stack('scripts')

</body>
</html>
