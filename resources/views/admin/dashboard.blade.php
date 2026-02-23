<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">

                {{ __('Admin Dashboard - Kelola Skincare') }}

            </h2>

            <span class="bg-pink-100 text-pink-700 px-4 py-1 rounded-full text-sm font-bold shadow-sm">

                Mode Administrator

            </span>

        </div>

    </x-slot>



    <div class="py-12 bg-pink-50 min-h-screen">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">



            @if(session('success'))

                <div class="mb-6 p-4 bg-green-500 text-white rounded-2xl shadow-lg border-none flex items-center">

                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>

                    </svg>

                    {{ session('success') }}

                </div>

            @endif



            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-pink-100">

                    <p class="text-gray-500 text-sm">Total Produk</p>

                    <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Product::count() }}</h3>

                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-pink-100">

                    <p class="text-gray-500 text-sm">Total Kategori</p>

                    <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Category::count() }}</h3>

                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-pink-100">

                    <p class="text-gray-500 text-sm">Admin Aktif</p>

                    <h3 class="text-2xl font-bold text-pink-600">{{ Auth::user()->name }}</h3>

                </div>

            </div>



            <div class="mt-8 bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-white">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-pink-600 w-2 h-8 rounded-full mr-3"></span>
                            Pesanan Terbaru ✨
                        </h3>
                        <a href="{{ route('admin.orders.index') }}" class="text-[10px] font-black text-pink-600 uppercase tracking-widest hover:underline">
                            Lihat Semua →
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-pink-600 text-[10px] font-black uppercase tracking-[0.2em] border-b border-pink-50">
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Pelanggan</th>
                                    <th class="px-4 py-3">Total</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @php
                                    // Mengambil 5 pesanan terbaru secara langsung dari Model
                                    $recentOrders = \App\Models\Order::with('user')->latest()->take(5)->get();
                                @endphp

                                @forelse($recentOrders as $order)
                                <tr class="hover:bg-pink-50/30 transition-colors">
                                    <td class="px-4 py-4 text-xs font-bold text-gray-400">#{{ $order->id }}</td>
                                    <td class="px-4 py-4">
                                        <p class="text-sm font-bold text-gray-800">{{ $order->user->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium lowercase italic">{{ $order->user->email }}</p>
                                    </td>
                                    <td class="px-4 py-4 text-sm font-black text-pink-600 italic">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                            {{ $order->status == 'success' ? 'bg-green-100 text-green-600' : '' }}
                                            {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                            {{ $order->status == 'dikirim' ? 'bg-blue-100 text-blue-600' : '' }}
                                            {{ $order->status == 'selesai' ? 'bg-purple-100 text-purple-600' : '' }}
                                            {{ $order->status == 'dibatalkan' ? 'bg-red-100 text-red-600' : '' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-10 text-center">
                                        <p class="text-gray-400 italic text-sm">Belum ada transaksi masuk.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center pb-12">
                <a href="{{ route('admin.products.index') }}" class="text-pink-600 font-bold hover:underline text-sm uppercase tracking-widest">
                    Lihat Katalog Produk →
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
