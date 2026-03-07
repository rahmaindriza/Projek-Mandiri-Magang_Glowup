<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">
            {{ __('Riwayat Pesanan Saya ✨') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- MENU TAB SEPERTI SHOPEE --}}
            <div class="flex border-b border-pink-100 mb-8 overflow-x-auto bg-white rounded-t-3xl shadow-sm">
                <button onclick="filterOrder('semua')" class="tab-btn px-6 py-4 text-sm font-bold text-pink-600 border-b-2 border-pink-600 whitespace-nowrap">Semua</button>
                <button onclick="filterOrder('belum bayar')" class="tab-btn px-6 py-4 text-sm font-bold text-gray-400 hover:text-pink-600 whitespace-nowrap">Belum Bayar</button>
                <button onclick="filterOrder('dikemas')" class="tab-btn px-6 py-4 text-sm font-bold text-gray-400 hover:text-pink-600 whitespace-nowrap">Dikemas</button>
                <button onclick="filterOrder('dikirim')" class="tab-btn px-6 py-4 text-sm font-bold text-gray-400 hover:text-pink-600 whitespace-nowrap">Dikirim</button>
                <button onclick="filterOrder('selesai')" class="tab-btn px-6 py-4 text-sm font-bold text-gray-400 hover:text-pink-600 whitespace-nowrap">Selesai</button>
            </div>

            <div class="space-y-6">
                @forelse($orders as $order)
                    <div class="order-card bg-white rounded-3xl shadow-sm border border-white p-6 transition-all hover:shadow-md" data-status="{{ $order->status }}">
                        <div class="flex justify-between items-center border-b border-gray-50 pb-4 mb-4">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">ID Pesanan: #{{ $order->id }}</span>
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ $order->status == 'belum bayar' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                {{ $order->status == 'dikemas' ? 'bg-orange-100 text-orange-600' : '' }}
                                {{ $order->status == 'dikirim' ? 'bg-blue-100 text-blue-600' : '' }}
                                {{ $order->status == 'selesai' ? 'bg-green-100 text-green-600' : '' }}">
                                {{ $order->status }}
                            </span>
                        </div>

                        <div class="flex gap-6">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800">Total Pembayaran:
                                    <span class="text-pink-600 text-lg">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </h4>
                                <p class="text-xs text-gray-400 mt-1 italic">📍 {{ $order->address }}</p>
                            </div>

                            <div class="flex items-center gap-3">
                                {{-- 1. JIKA DIKIRIM: TOMBOL PESANAN SELESAI --}}
                                @if($order->status == 'dikirim')
                                    <form action="{{ route('orders.selesai', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-blue-700 shadow-lg transition">
                                            ✅ Pesanan Diterima
                                        </button>
                                    </form>

                                {{-- 2. JIKA SELESAI: TOMBOL BELI LAGI & ULAS --}}
                                @elseif($order->status == 'selesai')
                                    <a href="{{ route('customer.katalog') }}" class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-pink-100 hover:text-pink-600 transition">
                                        🛍️ Beli Lagi
                                    </a>
                                    {{-- Tombol Ulas (Asumsi rute sudah ada) --}}
                                    <a href="{{ route('customer.reviews.create', ['product_id' => $order->items->first()->product_id ?? 0]) }}"
                                       class="bg-pink-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-pink-700 shadow-lg transition">
                                        ⭐ Beri Ulasan
                                    </a>

                                {{-- 3. JIKA BELUM BAYAR: TOMBOL DETAIL / BAYAR --}}
                                @elseif($order->status == 'belum bayar')
                                    <a href="{{ route('orders.show', $order->id) }}" class="bg-yellow-500 text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-yellow-600 transition">
                                        💳 Bayar Sekarang
                                    </a>
                                @endif

                                <a href="{{ route('orders.show', $order->id) }}" class="p-2.5 bg-gray-50 text-gray-400 rounded-xl hover:bg-pink-50 hover:text-pink-600 transition">
                                    👁️
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-[3rem]">
                        <p class="text-gray-400 italic">Belum ada pesanan di kategori ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- SCRIPT FILTER TAB --}}
    <script>
        function filterOrder(status) {
            const cards = document.querySelectorAll('.order-card');
            const btns = document.querySelectorAll('.tab-btn');

            // Ubah gaya tombol
            btns.forEach(btn => {
                btn.classList.remove('text-pink-600', 'border-b-2', 'border-pink-600');
                btn.classList.add('text-gray-400');
                if(btn.innerText.toLowerCase() === status) {
                    btn.classList.add('text-pink-600', 'border-b-2', 'border-pink-600');
                    btn.classList.remove('text-gray-400');
                }
            });

            // Filter Kartu
            cards.forEach(card => {
                if (status === 'semua' || card.getAttribute('data-status') === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>
