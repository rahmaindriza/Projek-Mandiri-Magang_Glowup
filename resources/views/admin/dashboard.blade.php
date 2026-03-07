    <x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">Admin Dashboard</h2>
                <a href="{{ route('admin.laporan.index') }}"
                    class="bg-white text-pink-600 border border-pink-200 px-4 py-1 rounded-full text-xs font-black uppercase shadow-sm">💰
                    Laporan Keuangan</a>
            </div>
        </x-slot>

        <div class="py-12 bg-pink-50 min-h-screen">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

                {{-- FITUR BARU: NOTIFIKASI STOK MENIPIS --}}
                @if (isset($lowStockProducts) && $lowStockProducts->count() > 0)
                    <div class="mb-8 p-6 bg-white border-l-8 border-red-500 rounded-3xl shadow-sm animate-pulse">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div class="flex items-center">
                                <div class="p-3 bg-red-100 rounded-2xl mr-4">
                                    <span class="text-2xl">⚠️</span>
                                </div>
                                <div>
                                    <h4 class="text-lg font-black text-red-700 uppercase tracking-tight">Peringatan Stok
                                        Kritis!</h4>
                                    <p class="text-sm text-gray-500 font-medium">Ada {{ $lowStockProducts->count() }}
                                        produk skincare dengan stok di bawah 5 pcs.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach ($lowStockProducts as $low)
                                <div class="flex items-center p-3 bg-pink-50/50 rounded-2xl border border-red-100">
                                    <img src="{{ asset('storage/' . $low->image) }}"
                                        class="w-12 h-12 object-cover rounded-xl mr-3 border border-white">
                                    <div class="flex-1">
                                        <p class="text-xs font-bold text-gray-800 line-clamp-1">{{ $low->name }}</p>
                                        <p class="text-[10px] text-red-600 font-black italic">Sisa: {{ $low->stock }}
                                            pcs</p>
                                    </div>
                                    <a href="{{ route('admin.stock.index', ['product_id' => $low->id]) }}"
                                        class="ml-2 p-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-sm">
                                        <span class="text-xs">➕</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Statistik Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-pink-100">
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Total Produk</p>
                        <h3 class="text-2xl font-black text-gray-800">{{ \App\Models\Product::count() }}</h3>
                    </div>
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-pink-100">
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Total Kategori</p>
                        <h3 class="text-2xl font-black text-gray-800">{{ \App\Models\Category::count() }}</h3>
                    </div>
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-pink-100 border-t-4 border-t-pink-400">
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Stok Gudang</p>
                        <h3 class="text-2xl font-black text-pink-600">{{ \App\Models\Product::sum('stock') }} <span
                                class="text-xs">pcs</span></h3>
                    </div>
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-pink-100 border-l-4 border-l-pink-600">
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Total Omzet</p>
                        <h3 class="text-2xl font-black text-pink-600">Rp
                            {{ number_format(\App\Models\Order::whereIn('status', ['success', 'selesai'])->sum('total_price'), 0, ',', '.') }}
                        </h3>
                    </div>
                </div>

                {{-- AREA GRAFIK
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-pink-100 mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <span class="bg-pink-600 w-2 h-8 rounded-full mr-3"></span> Tren Penjualan Skincare 💖
                    </h3>
                    <div style="position: relative; height:300px; width:100%">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div> --}}


                {{-- Tabel Pesanan Terbaru --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-pink-50 p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center"><span
                            class="bg-pink-600 w-2 h-8 rounded-full mr-3"></span> Pesanan Terbaru ✨</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-pink-600 text-[10px] font-black uppercase border-b border-pink-50">
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Pelanggan</th>
                                    <th class="px-4 py-3">Total</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($orders as $order)
                                    <tr class="hover:bg-pink-50/30 transition-colors">
                                        <td class="px-4 py-4 text-xs font-bold text-gray-400">#{{ $order->id }}</td>
                                        <td class="px-4 py-4">
                                            <p class="text-sm font-bold text-gray-800">{{ $order->user->name }}</p>
                                        </td>
                                        <td class="px-4 py-4 text-sm font-black text-pink-600 italic">Rp
                                            {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-4 text-center">
                                            <span
                                                class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $order->status == 'success' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
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
    </x-app-layout>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const canvas = document.getElementById('salesChart');
                if (canvas) {
                    new Chart(canvas.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($months) !!},
                            datasets: [{
                                label: 'Omzet (Rp)',
                                data: {!! json_encode($totals) !!},
                                borderColor: '#db2777',
                                backgroundColor: 'rgba(219, 39, 119, 0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#db2777',
                                pointRadius: 5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return 'Rp ' + value.toLocaleString();
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
