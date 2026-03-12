<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-playfair font-black text-3xl text-gray-800 tracking-tight">Admin <span class="text-pink-600">Overview</span></h2>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-[0.2em] mt-1">GlowUp Business Monitor</p>
            </div>
            <a href="{{ route('admin.laporan.index') }}"
                class="inline-flex items-center bg-white text-pink-600 border-2 border-pink-100 px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-pink-50 transition-all active:scale-95">
                <span class="mr-2 text-sm">💰</span> Laporan Keuangan
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-[#FFF9FB] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- NOTIFIKASI STOK MENIPIS - DESIGN BARU ✨ --}}
            @if (isset($lowStockProducts) && $lowStockProducts->count() > 0)
                <div class="mb-10 bg-white rounded-[2.5rem] border-2 border-red-50 overflow-hidden shadow-sm">
                    <div class="bg-red-500 px-8 py-3 flex items-center justify-between">
                        <div class="flex items-center text-white">
                            <span class="text-lg mr-2">⚠️</span>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Peringatan Stok Kritis</span>
                        </div>
                        <span class="bg-white text-red-600 text-[10px] font-black px-3 py-1 rounded-full">{{ $lowStockProducts->count() }} Produk</span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($lowStockProducts as $low)
                                <div class="flex items-center p-4 bg-red-50/30 rounded-[1.5rem] border border-red-100 group hover:bg-white hover:shadow-md transition-all duration-300">
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $low->image) }}" class="w-14 h-14 object-cover rounded-xl mr-4 border-2 border-white shadow-sm">
                                        <span class="absolute -top-2 -right-1 bg-red-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-md">{{ $low->stock }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-gray-800 truncate">{{ $low->name }}</p>
                                        <p class="text-[10px] text-red-500 font-bold italic mt-1">Segera Restock!</p>
                                    </div>
                                    <a href="{{ route('admin.stock.index') }}" class="ml-3 p-2.5 bg-white text-red-500 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- STATISTIK GRID - BENTO STYLE 🍱 --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-white p-8 rounded-[2.5rem] border border-pink-50 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Koleksi Produk</p>
                        <h3 class="text-3xl font-black text-gray-800">{{ \App\Models\Product::count() }}</h3>
                        <p class="text-[10px] text-pink-400 font-bold mt-2">Dalam {{ \App\Models\Category::count() }} Kategori</p>
                    </div>
                    <span class="absolute -right-4 -bottom-4 text-7xl opacity-5 group-hover:scale-110 transition-transform duration-500">🧴</span>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] border border-pink-50 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Transaksi</p>
                        <h3 class="text-3xl font-black text-gray-800">{{ \App\Models\Order::count() }}</h3>
                        <p class="text-[10px] text-indigo-400 font-bold mt-2">Semua Status</p>
                    </div>
                    <span class="absolute -right-4 -bottom-4 text-7xl opacity-5 group-hover:scale-110 transition-transform duration-500">🛍️</span>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] border border-pink-50 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Unit Terjual</p>
                        <h3 class="text-3xl font-black text-pink-600">{{ \App\Models\Order::where('status', 'selesai')->count() * 2 }} <span class="text-sm">pcs</span></h3>
                        <p class="text-[10px] text-pink-400 font-bold mt-2">Produk GlowUp</p>
                    </div>
                    <span class="absolute -right-4 -bottom-4 text-7xl opacity-5 group-hover:scale-110 transition-transform duration-500">📦</span>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] border border-pink-50 shadow-sm relative overflow-hidden group bg-gradient-to-br from-white to-pink-50/30">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Omzet</p>
                        <h3 class="text-2xl font-black text-pink-600 leading-tight">Rp {{ number_format(\App\Models\Order::whereIn('status', ['success', 'selesai'])->sum('total_price'), 0, ',', '.') }}</h3>
                        <p class="text-[10px] text-emerald-500 font-bold mt-2">↑ Laporan Berhasil</p>
                    </div>
                    <span class="absolute -right-4 -bottom-4 text-7xl opacity-10 group-hover:scale-110 transition-transform duration-500">💰</span>
                </div>
            </div>

            {{-- GRAFIK PENJUALAN 📈 --}}
            <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-pink-50 mb-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="font-playfair font-bold text-xl text-gray-800 flex items-center">
                        <span class="w-2 h-6 bg-pink-600 rounded-full mr-3"></span> Tren Penjualan 2026
                    </h3>
                    <select class="text-[10px] font-black uppercase border-pink-100 rounded-xl focus:ring-pink-500 text-gray-500">
                        <option>Tahun Ini</option>
                        <option>Bulan Ini</option>
                    </select>
                </div>
                <div class="h-[350px] w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            {{-- TABEL PESANAN TERBARU --}}
            <div class="bg-white rounded-[3rem] shadow-sm border border-pink-50 overflow-hidden">
                <div class="p-8 border-b border-pink-50 flex items-center justify-between">
                    <h3 class="font-playfair font-bold text-xl text-gray-800">Antrean Pesanan ✨</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-[10px] font-black text-pink-600 uppercase tracking-widest hover:underline">Kelola Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-pink-50/30 text-pink-600 text-[9px] font-black uppercase tracking-[0.2em]">
                                <th class="px-8 py-4">ID Transaksi</th>
                                <th class="px-8 py-4">Nama Pelanggan</th>
                                <th class="px-8 py-4">Total Bayar</th>
                                <th class="px-8 py-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-pink-50/20 transition-all group">
                                    <td class="px-8 py-5 text-xs font-bold text-gray-400 group-hover:text-pink-600 transition-colors">#GLOW-{{ $order->id }}</td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-[10px] font-bold text-pink-600 mr-3">
                                                {{ substr($order->user->name, 0, 1) }}
                                            </div>
                                            <p class="text-sm font-bold text-gray-700">{{ $order->user->name }}</p>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-sm font-black text-gray-800 italic">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest
                                            {{ $order->status == 'selesai' || $order->status == 'success' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('salesChart').getContext('2d');
                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(219, 39, 119, 0.2)');
                gradient.addColorStop(1, 'rgba(219, 39, 119, 0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($months) !!},
                        datasets: [{
                            label: 'Pendapatan',
                            data: {!! json_encode($totals) !!},
                            borderColor: '#db2777',
                            backgroundColor: gradient,
                            borderWidth: 4,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#db2777',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                grid: { display: false },
                                ticks: {
                                    font: { size: 10, weight: 'bold' },
                                    callback: function(value) { return 'Rp ' + value.toLocaleString(); }
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10, weight: 'bold' } }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
