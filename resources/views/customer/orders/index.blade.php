<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight tracking-tight">
            {{ __('Riwayat Belanja Cantikmu ✨') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50/50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-white flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Transaksi</p>
                        <h3 class="text-3xl font-black text-gray-800">{{ $orders->count() }}</h3>
                    </div>
                    <div class="text-4xl">🛍️</div>
                </div>
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-white flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Lunas</p>
                        <h3 class="text-3xl font-black text-green-500">{{ $orders->where('status', 'success')->count() }}</h3>
                    </div>
                    <div class="text-4xl">✅</div>
                </div>
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-white flex items-center justify-between text-center">
                    <a href="{{ route('customer.katalog') }}" class="w-full text-xs font-black text-pink-600 hover:scale-105 transition-transform uppercase tracking-tighter">
                        Tambah Koleksi Skincare →
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-2xl shadow-pink-100 sm:rounded-[3rem] border border-white">
                <div class="p-10">
                    <h3 class="text-xl font-bold text-gray-800 mb-8 border-b border-pink-50 pb-4">Daftar Pesananlara</h3>

                    @if($orders->count() > 0)
                        <div class="space-y-6">
                            @foreach($orders as $order)
                                <div class="group relative bg-pink-50/30 rounded-[2rem] p-6 border border-transparent hover:border-pink-200 hover:bg-white transition-all duration-500 shadow-sm hover:shadow-xl">
                                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                                        <div class="flex items-center space-x-6">
                                            <div class="h-16 w-16 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-inner border border-pink-50">
                                                📦
                                            </div>
                                            <div>
                                                <p class="text-xs font-black text-pink-600 uppercase tracking-widest mb-1">#INV-{{ $order->id }}</p>
                                                <p class="text-lg font-bold text-gray-800 tracking-tighter">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                            </div>
                                        </div>

                                        <div class="flex flex-col items-end gap-3">
                                            <span class="px-5 py-2 rounded-full text-[9px] font-black uppercase tracking-[0.2em] shadow-sm
                                                {{ $order->status == 'success' ? 'bg-green-500 text-white' : '' }}
                                                {{ $order->status == 'pending' ? 'bg-yellow-400 text-white' : '' }}
                                                {{ $order->status == 'dikirim' ? 'bg-blue-500 text-white' : '' }}
                                                {{ $order->status == 'selesai' ? 'bg-purple-500 text-white' : '' }}">
                                                {{ $order->status }}
                                            </span>

                                            <div class="flex space-x-2">
                                                <a href="{{ route('orders.show', $order->id) }}" class="text-[10px] font-black text-pink-600 bg-white border border-pink-100 px-6 py-2 rounded-xl hover:bg-pink-600 hover:text-white transition-all shadow-sm">
                                                    DETAIL
                                                </a>
                                                @if($order->status == 'pending')
                                                    <button onclick="window.snap.pay('{{ $order->snap_token }}')" class="text-[10px] font-black text-white bg-gray-900 px-6 py-2 rounded-xl hover:bg-pink-600 transition-all shadow-lg">
                                                        BAYAR
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-20">
                            <div class="text-6xl mb-6 opacity-20">🛍️</div>
                            <p class="text-gray-400 font-bold italic">Belum ada jejak pesanan cantikmu di sini.</p>
                            <a href="{{ route('customer.katalog') }}" class="inline-block mt-8 px-10 py-4 bg-pink-600 text-white font-black rounded-full shadow-xl shadow-pink-100 hover:scale-105 transition-all uppercase tracking-widest text-[10px]">
                                Jelajahi Katalog ✨
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
