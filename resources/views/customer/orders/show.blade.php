<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">
            {{ __('Invoice Pesanan Saya ✨') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4 bg-[#FFF9FB] min-h-screen flex justify-center items-start">

        <div class="w-full max-w-2xl mx-auto">

            <div class="mb-6 px-4 flex justify-between items-center">
                <a href="{{ route('customer.orders.index') }}" class="text-[10px] font-black text-gray-400 hover:text-pink-600 transition tracking-widest uppercase flex items-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Riwayat
                </a>
                <button onclick="window.print()" class="text-[10px] font-black text-pink-400 hover:text-pink-600 transition tracking-widest uppercase">
                    Cetak Invoice 🖨️
                </button>
            </div>

            <div class="bg-white rounded-[3rem] shadow-[0_20px_50px_rgba(236,72,153,0.1)] border border-white overflow-hidden">

                {{-- HEADER INVOICE --}}
                <div class="bg-pink-600 p-10 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 opacity-10 translate-x-10 -translate-y-10">
                        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>

                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-80 mb-2">GlowUp Official Invoice</p>
                            <h1 class="text-4xl font-black tracking-tighter">#INV-{{ $order->id }}</h1>
                            <p class="text-xs mt-2 opacity-90 italic">{{ $order->created_at->format('d F Y • H:i') }}</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-md px-5 py-2.5 rounded-2xl border border-white/30 text-center">
                            <span class="text-[10px] font-black uppercase tracking-widest block">Status</span>
                            <span class="text-xs font-bold italic">{{ strtoupper($order->status) }} ✨</span>
                        </div>
                    </div>
                </div>

                <div class="p-10">
                    {{-- INFO PEMBELI & ALAMAT --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">
                        <div>
                            <p class="text-[10px] font-black text-pink-600 uppercase tracking-widest mb-3 italic">Ditujukan Untuk:</p>
                            <p class="text-xl font-black text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-gray-500 text-xs mt-1">{{ Auth::user()->email }}</p>
                            <p class="text-gray-500 text-xs">{{ $order->phone ?? 'No WhatsApp belum tersedia' }}</p>
                        </div>
                        <div class="md:text-right">
                            <p class="text-[10px] font-black text-pink-600 uppercase tracking-widest mb-3 italic">Alamat Pengiriman:</p>
                            <p class="text-gray-600 font-bold text-sm leading-relaxed">
                                {{ $order->address }}
                            </p>
                            <p class="text-gray-400 text-[10px] mt-2 uppercase tracking-tighter">{{ $order->province }}, {{ $order->city }}</p>
                        </div>
                    </div>

                    {{-- DAFTAR BARANG ✨ --}}
                    <div class="mb-12">
                        <p class="text-[10px] font-black text-pink-600 uppercase tracking-widest mb-6 italic">Rincian Produk:</p>
                        <div class="space-y-6">
                            @foreach($order->items as $item)
                            <div class="flex items-center gap-5 bg-gray-50/50 p-4 rounded-[2rem] border border-gray-100">
                                <div class="w-20 h-20 bg-white rounded-2xl overflow-hidden shadow-sm flex-shrink-0">
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover" alt="{{ $item->product->name }}">
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800 text-sm">{{ $item->product->name }}</h4>
                                    <p class="text-xs text-gray-400 mt-1">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-gray-800 text-sm">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- TOTAL & PEMBAYARAN --}}
                    <div class="bg-pink-50/50 rounded-[2.5rem] p-8 mb-10 border border-pink-100 flex flex-col sm:flex-row justify-between items-center gap-6">
                        <div class="text-center sm:text-left">
                            <p class="text-gray-400 font-bold text-[10px] uppercase tracking-[0.2em] mb-1">Total Netto</p>
                            <h2 class="text-4xl font-black text-pink-600 tracking-tighter">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </h2>
                        </div>

                        @if($order->status == 'belum bayar')
                            <button onclick="window.snap.pay('{{ $order->snap_token }}')"
                                    class="w-full sm:w-auto bg-pink-600 text-white px-10 py-5 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-pink-200 hover:bg-pink-700 transition transform hover:scale-105 active:scale-95">
                                BAYAR SEKARANG 💳
                            </button>
                        @else
                            <div class="bg-white px-8 py-3 rounded-2xl border-2 border-dashed border-green-200">
                                <p class="text-green-500 font-black text-sm italic uppercase tracking-widest">Lunas Terverifikasi ✨</p>
                            </div>
                        @endif
                    </div>

                    {{-- FOOTER INVOICE --}}
                    <div class="flex flex-col items-center gap-4 border-t border-gray-100 pt-8 mt-4">
                        <div class="flex gap-4">
                             <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 text-xs">✨</div>
                             <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 text-xs">🧴</div>
                             <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 text-xs">💖</div>
                        </div>
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.5em]">GlowUp Skincare Official</p>
                        <p class="text-[8px] text-gray-300 italic">Terima kasih telah mempercayakan kecantikan kulitmu pada kami.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
