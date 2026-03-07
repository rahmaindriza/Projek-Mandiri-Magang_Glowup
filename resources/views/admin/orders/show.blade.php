<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight tracking-tighter">
            {{ __('Rincian Pesanan Masuk') }} 🛍️
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 px-4">
                <a href="{{ route('admin.orders.index') }}"
                    class="text-[10px] font-black text-gray-400 hover:text-pink-600 transition tracking-widest uppercase flex items-center">
                    <span class="mr-2">←</span> Kembali ke Daftar Pesanan
                </a>
            </div>

            <div class="bg-white rounded-[3rem] shadow-xl shadow-pink-100/50 border border-white overflow-hidden">

                <div class="bg-pink-600 p-10 text-white relative overflow-hidden rounded-t-[3rem]">
                    <div
                        class="absolute top-0 right-0 p-8 opacity-20 text-8xl italic font-black uppercase tracking-tighter text-white">
                        INV
                    </div>

                    <div class="flex justify-between items-center relative z-10">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-pink-200 mb-1">ID Transaksi
                            </p>
                            <h1 class="text-4xl font-black italic tracking-tighter">#INV-{{ $order->id }}</h1>
                            <p class="text-[10px] mt-2 font-bold opacity-80 uppercase">
                                {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                        <div class="text-right">
                            <span
                                class="px-6 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg bg-white/20 border border-white/30">
                                {{ $order->status }} ✨
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12 pb-10 border-b border-pink-50">
                        <div>
                            <p
                                class="text-[10px] font-black text-pink-600 uppercase tracking-widest mb-4 flex items-center">
                                <span class="w-1.5 h-4 bg-pink-600 rounded-full mr-2"></span> Informasi Pembeli
                            </p>
                            <h3 class="text-xl font-black text-gray-800">{{ $order->user->name }}</h3>
                            <p class="text-sm text-gray-500 font-medium">{{ $order->user->email }}</p>
                        </div>
                        <div class="md:text-right">
                            <p
                                class="text-[10px] font-black text-pink-600 uppercase tracking-widest mb-4 flex items-center md:justify-end">
                                <span class="w-1.5 h-4 bg-pink-600 rounded-full mr-2"></span> Alamat Pengiriman
                            </p>
                            <p class="text-gray-600 font-bold text-sm leading-relaxed italic">
                                📍 {{ $order->address }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-12">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 px-2">Item yang
                            Harus Disiapkan</p>
                        <div class="space-y-4">
                            @foreach ($order->orderItems as $item)
                                <div
                                    class="group flex items-center justify-between p-5 bg-pink-50/30 rounded-[2rem] border border-transparent hover:border-pink-100 hover:bg-white transition-all duration-300">
                                    <div class="flex items-center space-x-6">
                                        <div class="relative">
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                class="w-16 h-16 rounded-2xl object-cover shadow-sm group-hover:scale-105 transition-transform duration-500 border-2 border-white">
                                            <span
                                                class="absolute -top-2 -right-2 bg-pink-600 text-white text-[8px] font-black px-2 py-1 rounded-lg shadow-md">x{{ $item->quantity }}</span>
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-800 text-sm mb-1 uppercase tracking-tight">
                                                {{ $item->product->name }}</p>
                                            <p class="text-[10px] text-pink-400 font-bold uppercase italic">ID:
                                                #PROD-{{ $item->product->id }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400 font-bold line-through mb-0.5 opacity-50">Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</p>
                                        <p class="font-black text-pink-600 text-sm italic">Rp
                                            {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-pink-50 rounded-[2.5rem] p-8 border border-pink-100">
                        <div class="flex justify-between items-center text-pink-600">
                            <div>
                                <p class="text-[10px] font-black text-pink-400 uppercase tracking-widest mb-1">Total
                                    Pendapatan</p>
                                <p class="text-xs font-bold italic opacity-90">Termasuk pajak & biaya layanan ✨</p>
                            </div>
                            <div class="text-right">
                                <h2 class="text-4xl font-black italic tracking-tighter">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </h2>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 pt-8 border-t border-gray-100 text-center">
                        <p class="text-[9px] font-black text-pink-200 uppercase tracking-[0.4em]">GlowUp Official
                            Dashboard &bull; 2026</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
