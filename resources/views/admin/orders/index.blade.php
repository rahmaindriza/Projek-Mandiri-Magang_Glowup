<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">
            {{ __('Kelola Pesanan Masuk ✨') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-[2.5rem] border border-white p-8">

                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-xl font-bold text-gray-800">Manajemen Transaksi</h3>
                    <div
                        class="bg-pink-100 text-pink-600 px-4 py-2 rounded-2xl text-xs font-black uppercase tracking-widest">
                        Total: {{ $orders->count() }} Pesanan
                    </div>
                </div>

                <div class="overflow-x-auto">
                    @if (session('success'))
                        <div
                            class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl text-xs font-bold">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-pink-600 text-[10px] font-black uppercase tracking-[0.2em] border-b border-pink-50">
                                <th class="px-4 py-4 text-center">ID</th>
                                <th class="px-4 py-4">Pembeli</th>
                                <th class="px-4 py-4 text-right">Total</th>
                                <th class="px-4 py-4 text-center">Status Saat Ini</th>
                                <th class="px-4 py-4 text-center">Aksi Admin</th>
                                <th class="px-4 py-4 text-center">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($orders as $order)
                                <tr class="hover:bg-pink-50/30 transition-colors">
                                    <td class="px-4 py-6 text-center font-bold text-gray-400">#{{ $order->id }}</td>
                                    <td class="px-4 py-6">
                                        <p class="font-bold text-gray-800">{{ $order->user->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium italic">📍
                                            {{ Str::limit($order->address, 30) }}</p>
                                    </td>
                                    <td class="px-4 py-6 text-right font-black text-pink-600">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>

                                    {{-- Kolom Status dengan Warna Dinamis --}}
                                    <td class="px-4 py-6 text-center">
                                        <span
                                            class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm
                                            {{ $order->status == 'belum bayar' || $order->status == 'pending' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                            {{ $order->status == 'dikemas' ? 'bg-orange-100 text-orange-600' : '' }}
                                            {{ $order->status == 'dikirim' ? 'bg-blue-100 text-blue-600' : '' }}
                                            {{ $order->status == 'selesai' ? 'bg-green-100 text-green-600' : '' }}
                                            {{ $order->status == 'dibatalkan' ? 'bg-red-100 text-red-600' : '' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>

                                    {{-- Kolom Tombol Aksi Admin Dinamis ✨ --}}
                                    <td class="px-4 py-6 text-center">
                                        @if ($order->status == 'dikemas')
                                            {{-- Muncul jika sudah bayar --}}
                                            <form action="{{ route('admin.orders.update', $order->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="dikirim">
                                                <button type="submit" class="...">
                                                    🚚 Kirim Barang
                                                </button>
                                            </form>
                                        @elseif($order->status == 'pending' || $order->status == 'belum bayar')
                                            {{-- Muncul jika customer belum bayar/pending --}}
                                            <form action="{{ route('admin.orders.update', $order->id) }}"
                                                method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="dikemas">
                                                <button type="submit"
                                                    class="bg-orange-500 text-white px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-orange-600 transition shadow-md">
                                                    📦 Konfirmasi Bayar
                                                </button>
                                            </form>
                                            <p class="text-[8px] text-gray-400 mt-1 italic">Menunggu pembayaran...</p>
                                        @elseif($order->status == 'dikirim')
                                            <span
                                                class="text-[9px] text-blue-400 font-bold italic uppercase tracking-widest border border-blue-100 px-3 py-1 rounded-lg">
                                                Dalam Perjalanan...
                                            </span>
                                        @elseif($order->status == 'selesai')
                                            <span
                                                class="text-[9px] text-green-500 font-bold italic uppercase tracking-widest bg-green-50 px-3 py-1 rounded-lg">
                                                Transaksi Selesai ✅
                                            </span>
                                        @else
                                            <span
                                                class="text-[9px] text-red-400 font-bold italic uppercase tracking-widest">
                                                {{ strtoupper($order->status) }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-6 text-center">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                            class="inline-flex items-center justify-center w-10 h-10 bg-white text-pink-600 rounded-xl border border-pink-100 hover:bg-pink-600 hover:text-white transition-all shadow-sm">
                                            👁️
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-20 text-center">
                                        <div class="text-5xl mb-4">📦</div>
                                        <p class="text-gray-400 italic">Belum ada pesanan masuk untuk diproses.</p>
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
