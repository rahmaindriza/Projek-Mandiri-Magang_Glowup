<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-playfair font-bold text-2xl text-pink-600 tracking-tight">
                {{ __('Riwayat Pesanan ✨') }}
            </h2>
            <span
                class="text-xs font-medium text-pink-400 bg-pink-50 px-3 py-1 rounded-full border border-pink-100 italic">
                GlowUp Your Beauty Dashboard
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-[#FFF9FB] min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- MODERN TAB NAVIGATION --}}
            <div
                class="flex p-1.5 space-x-1 bg-white/80 backdrop-blur-md rounded-2xl shadow-sm mb-10 sticky top-4 z-10 border border-pink-50/50 overflow-x-auto scrollbar-hide">
                <button onclick="filterOrder('semua')"
                    class="tab-btn flex-1 px-4 py-2.5 text-sm font-bold rounded-xl transition-all duration-300 bg-pink-600 text-white shadow-md shadow-pink-200 whitespace-nowrap">
                    Semua
                </button>
                <button onclick="filterOrder('belum bayar')"
                    class="tab-btn flex-1 px-4 py-2.5 text-sm font-bold text-gray-500 rounded-xl hover:bg-pink-50 hover:text-pink-600 transition-all duration-300 whitespace-nowrap">
                    Belum Bayar
                </button>
                <button onclick="filterOrder('dikemas')"
                    class="tab-btn flex-1 px-4 py-2.5 text-sm font-bold text-gray-500 rounded-xl hover:bg-pink-50 hover:text-pink-600 transition-all duration-300 whitespace-nowrap">
                    Dikemas
                </button>
                <button onclick="filterOrder('dikirim')"
                    class="tab-btn flex-1 px-4 py-2.5 text-sm font-bold text-gray-500 rounded-xl hover:bg-pink-50 hover:text-pink-600 transition-all duration-300 whitespace-nowrap">
                    Dikirim
                </button>
                <button onclick="filterOrder('selesai')"
                    class="tab-btn flex-1 px-4 py-2.5 text-sm font-bold text-gray-500 rounded-xl hover:bg-pink-50 hover:text-pink-600 transition-all duration-300 whitespace-nowrap">
                    Selesai
                </button>
            </div>

            <div class="space-y-8">
                @forelse($orders as $order)
                    <div class="order-card group bg-white rounded-[2.5rem] shadow-[0_10px_30px_rgba(0,0,0,0.02)] border border-pink-50/50 p-8 transition-all duration-500 hover:shadow-xl hover:shadow-pink-100/50 hover:-translate-y-1"
                        data-status="{{ $order->status }}">

                        {{-- CARD HEADER --}}
                        <div
                            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-pink-50 pb-6 mb-6">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-pink-50 rounded-xl">
                                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 400-8v4m0 4v4m0-4H8m4 0h4m-4-8a3 3 0 013-3h.01M9 21h6a2 2 0 002-2V9a2 2 0 00-2-2H9a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-pink-300 uppercase tracking-[0.2em]">
                                        Transaction ID</p>
                                    <p class="text-sm font-bold text-gray-700">#GLOW-{{ $order->id }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span
                                    class="text-[10px] text-gray-400 font-medium italic">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                <span
                                    class="px-5 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest
                                    {{ $order->status == 'belum bayar' ? 'bg-amber-50 text-amber-600 border border-amber-100' : '' }}
                                    {{ $order->status == 'dikemas' ? 'bg-orange-50 text-orange-600 border border-orange-100' : '' }}
                                    {{ $order->status == 'dikirim' ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : '' }}
                                    {{ $order->status == 'selesai' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : '' }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>

                        {{-- CARD BODY --}}
                        <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-6">
                            <div class="w-full">
                                <p class="text-[10px] font-black text-pink-300 uppercase tracking-[0.2em] mb-1">Total
                                    Order</p>
                                <h4 class="text-2xl font-black text-gray-800 tracking-tight">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </h4>
                                <div class="mt-3 flex items-start gap-2 text-gray-500">
                                    <svg class="w-4 h-4 mt-0.5 text-pink-300 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p class="text-xs leading-relaxed italic">{{ $order->address }}</p>
                                </div>
                            </div>

                            {{-- ACTION BUTTONS --}}
                            <div class="flex items-center gap-2 w-full md:w-auto">
                                @if ($order->status == 'dikirim')
                                    <form action="{{ route('customer.orders.selesai', $order->id) }}" method="POST"
                                        class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-indigo-600 text-white px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all duration-300 hover:scale-105 active:scale-95">
                                            Konfirmasi Diterima
                                        </button>
                                    </form>
                                @elseif($order->status == 'selesai')
                                    <a href="{{ route('customer.katalog') }}"
                                        class="flex-1 bg-white border-2 border-pink-100 text-pink-600 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] hover:bg-pink-50 transition-all duration-300 text-center">
                                        Beli Lagi
                                    </a>
                                    <a href="{{ route('customer.reviews.create', ['product_id' => $order->items->first()->product_id ?? 0]) }}"
                                        class="flex-1 bg-pink-600 text-white px-6 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] hover:bg-pink-700 shadow-lg shadow-pink-100 transition-all duration-300 hover:scale-105 text-center">
                                        Beri Ulasan
                                    </a>
                                @elseif($order->status == 'belum bayar')
                                    {{-- PERBAIKAN: Tombol Bayar Sekarang --}}
                                    <button type="button"
                                        onclick="payNow('{{ $order->snap_token }}', '{{ $order->id }}')"
                                        class="w-full bg-amber-500 text-white px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] hover:bg-amber-600 shadow-lg shadow-amber-100 transition-all duration-300 hover:scale-105 active:scale-95">
                                        Bayar Sekarang 💳
                                    </button>
                                @endif

                                <a href="{{ route('orders.show', $order->id) }}"
                                    class="p-3.5 bg-gray-50 text-gray-400 rounded-2xl hover:bg-pink-100 hover:text-pink-600 transition-all duration-300 group-hover:bg-pink-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-pink-100 shadow-inner">
                        <div class="bg-pink-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-pink-200" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 00-2-2m0 0V5a2 2 0 012-2h14a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Oops! Masih Kosong</h3>
                        <p class="text-sm text-gray-400 mt-2 italic px-8">Belum ada pesanan di kategori ini. Yuk, mulai
                            perawatan wajahmu hari ini! ✨</p>
                        <a href="{{ route('customer.katalog') }}"
                            class="inline-block mt-8 text-[10px] font-black text-pink-600 border-b-2 border-pink-600 pb-1 uppercase tracking-widest hover:text-pink-700 transition">Mulai
                            Belanja Sekarang</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function filterOrder(status) {
            const cards = document.querySelectorAll('.order-card');
            const btns = document.querySelectorAll('.tab-btn');
            btns.forEach(btn => {
                btn.classList.remove('bg-pink-600', 'text-white', 'shadow-md', 'shadow-pink-200');
                btn.classList.add('text-gray-500');
                if (btn.innerText.toLowerCase() === status) {
                    btn.classList.add('bg-pink-600', 'text-white', 'shadow-md', 'shadow-pink-200');
                    btn.classList.remove('text-gray-500');
                }
            });
            cards.forEach(card => {
                if (status === 'semua' || card.getAttribute('data-status') === status) {
                    card.style.display = 'block';
                    card.classList.add('animate-fade-in');
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        {{-- PERBAIKAN: Fungsi payNow yang sudah dibersihkan --}}
        function payNow(snapToken, orderId) {
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    fetch("/orders/" + orderId + "/update-status-manual", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ status: 'dikemas' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Pembayaran Berhasil! ✨',
                                text: 'Status pesananmu otomatis berubah jadi Dikemas.',
                                icon: 'success',
                                confirmButtonColor: '#db2777',
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.location.reload();
                    });
                },
                onPending: function(result) {
                    Swal.fire('Menunggu Pembayaran', 'Silakan selesaikan pembayaranmu.', 'info');
                },
                onError: function(result) {
                    Swal.fire('Gagal', 'Pembayaran gagal diproses.', 'error');
                }
            });
        }
    </script>
</x-app-layout>
