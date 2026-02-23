<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">
            {{ __('Invoice Pesanan Saya') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4 bg-pink-50/30 min-h-screen flex justify-center items-start">

        <div class="w-full max-w-2xl mx-auto">

            <div class="mb-6 px-4">
                <a href="{{ route('dashboard') }}" class="text-[10px] font-black text-gray-400 hover:text-pink-600 transition tracking-widest uppercase">
                    ← Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-pink-100 border border-white overflow-hidden">

                <div class="bg-pink-600 p-8 text-white relative">
                    <div class="flex justify-between items-center relative z-10">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-1">Invoice Number</p>
                            <h1 class="text-3xl font-black">#INV-{{ $order->id }}</h1>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl border border-white/30">
                            <span class="text-[10px] font-black uppercase tracking-widest italic">
                                {{ $order->status }} ✨
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-2 gap-8 mb-10">
                        <div>
                            <p class="text-[10px] font-black text-pink-600 uppercase tracking-widest mb-2">Informasi Pembeli</p>
                            <p class="text-lg font-bold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-gray-500 text-xs">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-pink-600 uppercase tracking-widest mb-2">Alamat Pengiriman</p>
                            <p class="text-gray-600 font-bold text-sm leading-relaxed">
                                📍 {{ $order->address }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-pink-50/50 rounded-3xl p-6 mb-10 border border-pink-100 flex justify-between items-center">
                        <div>
                            <p class="text-gray-400 font-bold text-[10px] uppercase tracking-widest mb-1">Total Pembayaran</p>
                            <h2 class="text-3xl font-black text-gray-900">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </h2>
                        </div>

                        @if($order->status == 'pending')
                            <button onclick="window.snap.pay('{{ $order->snap_token }}')"
                                    class="bg-pink-600 text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-pink-200 hover:bg-pink-700 transition">
                                BAYAR 💳
                            </button>
                        @else
                            <div class="text-right">
                                <p class="text-green-500 font-black text-lg italic uppercase">Lunas ✨</p>
                            </div>
                        @endif
                    </div>

                    <div class="text-center opacity-30 border-t border-gray-100 pt-6">
                        <p class="text-[10px] font-bold uppercase tracking-widest">GlowUp Skincare Official</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
