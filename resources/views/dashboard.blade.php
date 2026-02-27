<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">
            {{ __('Halo, ') }} {{ Auth::user()->name }}! ✨
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-white">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pesanan</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $orders->count() }}</h3>
                </div>

                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-white">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Status Terakhir</p>
                    <h3 class="text-lg font-bold text-pink-600 italic">
                        {{ $orders->first()->status ?? 'Belum Ada Pesanan' }}
                    </h3>
                </div>

                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-white text-center flex items-center justify-center">
                    <a href="{{ route('customer.katalog') }}"
                        class="text-sm font-bold text-pink-600 hover:text-pink-700 hover:underline tracking-tight transition-all">
                        Lanjut Belanja Skincare →
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-[2.5rem] border border-white">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Riwayat Pesanan Terakhir</h3>
                        <span class="text-[10px] bg-pink-50 text-pink-500 px-3 py-1 rounded-full font-bold uppercase">Personal Data</span>
                    </div>

                    @if ($orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-pink-600 text-xs font-bold uppercase tracking-widest border-b border-pink-50">
                                        <th class="px-4 py-3">ID Order</th>
                                        <th class="px-4 py-3">Tanggal</th>
                                        <th class="px-4 py-3">Total</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach ($orders as $order)
                                        <tr class="hover:bg-pink-50/30 transition-colors">
                                            <td class="px-4 py-4 font-bold text-gray-700">#{{ $order->id }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                            <td class="px-4 py-4 font-bold text-pink-600 italic">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                            <td class="px-4 py-4">
                                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $order->status == 'success' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('orders.show', $order->id) }}" class="text-[10px] font-bold text-pink-600 bg-pink-50 px-3 py-2 rounded-xl hover:bg-pink-100 transition uppercase tracking-widest">Detail 📋</a>
                                                    @if ($order->status == 'pending' && $order->snap_token)
                                                        <button onclick="window.snap.pay('{{ $order->snap_token }}')" class="text-[10px] font-bold text-white bg-pink-600 px-3 py-2 rounded-xl hover:bg-pink-700 transition uppercase tracking-widest shadow-sm">Bayar 💳</button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-5xl mb-4 opacity-50">🛍️</div>
                            <p class="text-gray-400 italic">Kamu belum pernah memesan apapun.</p>
                            <a href="{{ route('customer.katalog') }}" class="inline-block mt-6 px-10 py-4 bg-pink-600 text-white font-bold rounded-full text-xs uppercase tracking-widest">Mulai Cari Skincare ✨</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
