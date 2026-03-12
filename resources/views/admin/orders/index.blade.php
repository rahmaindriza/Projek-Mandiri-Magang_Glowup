<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-playfair font-black text-3xl text-gray-800 tracking-tight">
                    Pesanan <span class="text-pink-600">Masuk</span> ✨
                </h2>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-[0.2em] mt-1">GlowUp Order Management</p>
            </div>
            <div class="bg-white border-2 border-pink-100 px-6 py-2 rounded-2xl shadow-sm">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Total Antrean</p>
                <p class="text-lg font-black text-pink-600 leading-none">{{ $orders->count() }} <span class="text-[10px]">Pesanan</span></p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-[#FFF9FB] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-[1.5rem] text-xs font-bold flex items-center shadow-sm">
                    <span class="mr-3 text-lg">✅</span> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-[3rem] shadow-sm border border-pink-50 overflow-hidden">
                <div class="p-8 border-b border-pink-50 bg-gradient-to-r from-white to-pink-50/20">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="w-2 h-6 bg-pink-600 rounded-full mr-3"></span> Daftar Transaksi Terbaru
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-pink-600 text-[10px] font-black uppercase tracking-[0.2em] border-b border-pink-50">
                                <th class="px-8 py-5">ID & Tanggal</th>
                                <th class="px-8 py-5">Pelanggan</th>
                                <th class="px-8 py-5 text-right">Nominal</th>
                                <th class="px-8 py-5 text-center">Status Pembayaran</th>
                                <th class="px-8 py-5 text-center">Tindakan Admin</th>
                                <th class="px-8 py-5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($orders as $order)
                                <tr class="hover:bg-pink-50/20 transition-all group">
                                    <td class="px-8 py-6">
                                        <p class="text-xs font-black text-gray-400 group-hover:text-pink-600 transition-colors">#GLOW-{{ $order->id }}</p>
                                        <p class="text-[10px] text-gray-400 mt-1 italic">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-2xl bg-pink-100 flex items-center justify-center text-xs font-black text-pink-600 mr-4 shadow-sm border border-white">
                                                {{ substr($order->user->name, 0, 2) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-gray-800 truncate">{{ $order->user->name }}</p>
                                                <p class="text-[10px] text-gray-400 truncate max-w-[150px] italic">📍 {{ $order->address }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <p class="text-sm font-black text-gray-800 tracking-tight">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm border
                                            {{ $order->status == 'belum bayar' || $order->status == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}
                                            {{ $order->status == 'dikemas' ? 'bg-orange-50 text-orange-600 border-orange-100' : '' }}
                                            {{ $order->status == 'dikirim' ? 'bg-indigo-50 text-indigo-600 border-indigo-100' : '' }}
                                            {{ $order->status == 'selesai' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                                            {{ $order->status == 'dibatalkan' ? 'bg-red-50 text-red-600 border-red-100' : '' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        @if ($order->status == 'dikemas')
                                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="dikirim">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 shadow-md shadow-indigo-100 transition-all hover:scale-105 active:scale-95">
                                                    🚚 Kirim Barang
                                                </button>
                                            </form>
                                        @elseif($order->status == 'pending' || $order->status == 'belum bayar')
                                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="dikemas">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-orange-500 text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-orange-600 shadow-md shadow-orange-100 transition-all hover:scale-105 active:scale-95">
                                                    📦 Konfirmasi Bayar
                                                </button>
                                            </form>
                                            <p class="text-[8px] text-gray-400 mt-2 italic font-medium">Customer Belum Bayar</p>
                                        @elseif($order->status == 'dikirim')
                                            <div class="flex flex-col items-center">
                                                <span class="text-[9px] text-indigo-400 font-black uppercase tracking-widest bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-100 italic">On Delivery</span>
                                            </div>
                                        @elseif($order->status == 'selesai')
                                            <span class="text-[9px] text-emerald-500 font-black uppercase tracking-widest bg-emerald-50 px-3 py-1 rounded-lg border border-emerald-100">Closed Order ✅</span>
                                        @else
                                            <span class="text-[9px] text-red-400 font-black uppercase tracking-widest border border-red-100 px-3 py-1 rounded-lg">Canceled</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                           class="inline-flex items-center justify-center w-10 h-10 bg-white text-pink-600 rounded-2xl border-2 border-pink-50 hover:bg-pink-600 hover:text-white hover:border-pink-600 transition-all shadow-sm active:scale-90">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-24 text-center">
                                        <div class="w-20 h-20 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <span class="text-4xl text-pink-200">🛍️</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800">Antrean Kosong</h3>
                                        <p class="text-gray-400 italic text-sm mt-2">Belum ada pesanan masuk yang perlu diproses.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
