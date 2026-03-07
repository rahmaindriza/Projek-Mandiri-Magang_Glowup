<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $product->name }} - GlowUp</title>
    <link href="https://fonts.bunny.net/css?family=playfair+display:700|figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
    </style>
</head>

<body class="bg-pink-50/30 font-['Figtree'] antialiased">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-pink-100">
        <div class="max-w-7xl mx-auto px-4 h-20 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-playfair font-bold text-pink-600 tracking-tighter">GLOWUP</a>
            @php
                $backRoute = route('customer.katalog');
                if (auth()->check() && auth()->user()->role == 'admin') {
                    $backRoute = route('admin.products.index');
                }
            @endphp
            <a href="{{ $backRoute }}" class="text-sm font-bold text-gray-500 hover:text-pink-600 transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali Belanja
            </a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-16">
        {{-- Card Utama Produk --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white p-8 md:p-12 rounded-[3rem] shadow-2xl border border-white">
            <div class="rounded-[3rem] overflow-hidden shadow-2xl h-[500px]">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-105 transition duration-700">
            </div>

            <div class="flex flex-col justify-center">
                <span class="text-pink-600 font-bold tracking-widest uppercase text-xs mb-2 bg-pink-50 w-fit px-4 py-1 rounded-full italic">
                    {{ $product->category->name }}
                </span>
                <h1 class="text-4xl md:text-5xl font-playfair font-black text-gray-900 mb-4">{{ $product->name }}</h1>
                <p class="text-3xl font-light text-pink-500 mb-6 font-playfair italic">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

                <div class="text-gray-500 leading-relaxed mb-8 border-t border-gray-50 pt-6">
                    <h4 class="font-bold text-gray-800 mb-2 uppercase text-xs tracking-widest">Deskripsi Produk:</h4>
                    <p class="text-lg">{{ $product->description ?: 'Tidak ada deskripsi untuk produk ini.' }}</p>
                    <div class="mt-4 text-sm font-bold text-gray-400">
                        Stok Tersedia: <span class="text-pink-600">{{ $product->stock }} pcs</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4 mt-10">
                    @auth
                        <form action="{{ route('cart.store', $product->id) }}" method="POST" class="flex-grow">
                            @csrf
                            <input type="hidden" name="redirect_to" value="checkout">
                            <button type="submit" class="w-full py-4 bg-pink-600 text-white font-bold rounded-2xl shadow-lg hover:bg-pink-700 transition-all">
                                <span>Beli Sekarang ✨</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="w-full py-4 bg-gray-800 text-white text-center font-bold rounded-2xl hover:bg-gray-900 transition-all">
                            Login untuk Membeli 🛒
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- SEKSI DAFTAR ULASAN SAJA (Testimoni) --}}
        <div id="review-section" class="mt-20 border-t border-pink-100 pt-10">
            <h3 class="font-playfair font-bold text-3xl text-gray-800 mb-10 text-center italic">Sentuhan Cantik Mereka 🌸</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($product->reviews as $review)
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-pink-50 hover:shadow-xl transition-all duration-500">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="font-black text-gray-800 text-sm uppercase tracking-tighter">{{ $review->user->name }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-yellow-400 text-xs">
                                {{ str_repeat('⭐', $review->rating) }}
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm italic leading-relaxed">"{{ $review->comment }}"</p>
                    </div>
                @empty
                    <div class="md:col-span-2 bg-white/50 p-16 rounded-[3rem] text-center border-2 border-dashed border-pink-100">
                        <p class="text-gray-400 italic font-medium">Belum ada ulasan untuk produk ini. ✨</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil! ✨',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                background: '#fff',
                color: '#db2777',
                iconColor: '#db2777'
            });
        </script>
    @endif
</body>
</html>
