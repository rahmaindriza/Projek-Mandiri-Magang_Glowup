<x-app-layout>
    <div class="py-12 bg-[#FFF9FB] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- WELCOME SECTION ✨ --}}
            <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="font-playfair font-black text-3xl text-gray-800 tracking-tight">
                        Halo, <span class="text-pink-600">{{ Auth::user()->name }}!</span> ✨
                    </h2>
                    <p class="text-gray-400 text-sm mt-1 italic">Siap untuk tampil makin cantik hari ini?</p>
                </div>
                <a href="{{ route('customer.katalog') }}"
                   class="inline-flex items-center px-6 py-3 bg-pink-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-lg shadow-pink-200 hover:bg-pink-700 transition-all hover:scale-105 active:scale-95">
                    🛍️ Mulai Belanja
                </a>
            </div>

            {{-- BENTO STATS CARDS 🍱 --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10">
                <div class="bg-white p-6 rounded-[2.5rem] border border-pink-50 shadow-sm hover:shadow-md transition-all">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Order</p>
                    <div class="flex items-end justify-between">
                        <h3 class="text-3xl font-black text-gray-800">{{ $orders->count() }}</h3>
                        <span class="text-2xl opacity-20">📦</span>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2.5rem] border border-pink-50 shadow-sm hover:shadow-md transition-all">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Belum Bayar</p>
                    <div class="flex items-end justify-between">
                        <h3 class="text-3xl font-black text-amber-500">{{ $orders->where('status', 'belum bayar')->count() }}</h3>
                        <span class="text-2xl opacity-20">💳</span>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2.5rem] border border-pink-50 shadow-sm hover:shadow-md transition-all">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Dalam Proses</p>
                    <div class="flex items-end justify-between">
                        <h3 class="text-3xl font-black text-pink-600">{{ $orders->whereIn('status', ['dikemas', 'dikirim'])->count() }}</h3>
                        <span class="text-2xl opacity-20">🚚</span>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2.5rem] border border-pink-50 shadow-sm hover:shadow-md transition-all">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">GlowUp Points</p>
                    <div class="flex items-end justify-between">
                        <h3 class="text-3xl font-black text-indigo-600">{{ $orders->where('status', 'selesai')->count() * 10 }}</h3>
                        <span class="text-2xl opacity-20">✨</span>
                    </div>
                </div>
            </div>

            {{-- RECENT ORDERS VISUAL --}}
            <div class="bg-white rounded-[3rem] border border-pink-50 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-pink-50 flex justify-between items-center">
                    <h3 class="font-playfair font-bold text-xl text-gray-800">Pesanan Terakhirmu</h3>
                    <a href="{{ route('customer.orders.index') }}" class="text-[10px] font-black text-pink-600 uppercase tracking-widest hover:underline">Lihat Semua →</a>
                </div>

                <div class="p-8">
                    @forelse($orders->take(3) as $order)
                        <div class="flex flex-col md:flex-row items-center justify-between p-6 mb-4 rounded-[2rem] bg-pink-50/30 border border-pink-50/50 hover:bg-white hover:shadow-xl hover:shadow-pink-100/50 transition-all duration-500">
                            <div class="flex items-center gap-6 w-full md:w-auto">
                                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                                    {{ $order->status == 'selesai' ? '💖' : '🎁' }}
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-pink-300 uppercase tracking-widest">Order #GLOW-{{ $order->id }}</p>
                                    <h4 class="font-bold text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h4>
                                    <p class="text-[10px] text-gray-400 italic">{{ $order->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 mt-4 md:mt-0 w-full md:w-auto">
                                <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest
                                    {{ $order->status == 'belum bayar' ? 'bg-amber-100 text-amber-600' : '' }}
                                    {{ $order->status == 'dikemas' ? 'bg-orange-100 text-orange-600' : '' }}
                                    {{ $order->status == 'dikirim' ? 'bg-indigo-100 text-indigo-600' : '' }}
                                    {{ $order->status == 'selesai' ? 'bg-emerald-100 text-emerald-600' : '' }}
                                    {{ $order->status == 'dibatalkan' ? 'bg-red-100 text-red-600' : '' }}">
                                    {{ $order->status }}
                                </span>
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="p-3 bg-white text-gray-400 rounded-xl hover:text-pink-600 shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-gray-400 italic text-sm">Belum ada aktivitas belanja nih... 🛍️</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
