<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GlowUp - Beauty Skincare</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair+display:700|figtree:400,500,600&display=swap"
        rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
        }

        .hero-custom-bg {
            position: relative;
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url("{{ asset('storage/img/background.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .overlay-soft {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.6));
            z-index: 1;
        }

        .content-z {
            position: relative;
            z-index: 10;
        }

        .product-card:hover img {
            transform: scale(1.1);
        }
    </style>
</head>

<body class="antialiased bg-white font-['Figtree']">

    <nav class="glass-nav shadow-sm fixed top-0 w-full z-50 border-b border-pink-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <h1 class="text-3xl font-playfair font-bold text-pink-600 tracking-tighter">GLOWUP</h1>
                </div>
                <div class="hidden space-x-10 sm:flex">
                    <a href="#"
                        class="text-gray-800 hover:text-pink-600 text-sm font-semibold transition uppercase tracking-widest">Home</a>
                    <a href="#katalog"
                        class="text-gray-800 hover:text-pink-600 text-sm font-semibold transition uppercase tracking-widest">Shop</a>
                    <a href="#"
                        class="text-gray-800 hover:text-pink-600 text-sm font-semibold transition uppercase tracking-widest">Best
                        Seller</a>
                </div>
                <div class="flex items-center space-x-6">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-6 py-2.5 text-sm font-bold text-white bg-pink-600 rounded-full shadow-lg">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-sm font-bold text-gray-800 hover:text-pink-600 transition">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-6 py-2.5 text-sm font-bold text-white bg-pink-600 rounded-full hover:bg-pink-700 transition shadow-lg">Daftar</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <header class="hero-custom-bg">
        <div class="overlay-soft"></div>

        <div class="content-z max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mt-20">
            <span
                class="inline-block px-4 py-1.5 mb-6 text-xs font-bold tracking-widest text-pink-700 uppercase bg-white/90 rounded-full shadow-sm">
                New Collection 2026
            </span>

            <h2
                class="text-6xl font-playfair tracking-tight font-bold text-gray-900 sm:text-7xl md:text-8xl leading-tight">
                Pancarkan Pesona <br>
                <span class="text-pink-600 italic">Kulit Cantikmu</span>
            </h2>

            <p
                class="mt-8 text-xl text-gray-700 max-w-2xl mx-auto font-medium leading-relaxed bg-white/40 backdrop-blur-md rounded-2xl p-6 border border-white/50 shadow-sm">
                Temukan rangkaian skincare premium yang diformulasikan khusus dengan bahan aktif terbaik. Karena kulit
                cantik adalah investasi terbaikmu.
            </p>

            <div class="mt-12 flex justify-center gap-6">
                <a href="#katalog"
                    class="px-12 py-4 text-base font-bold rounded-full text-white bg-gray-900 hover:bg-pink-600 transition-all duration-300 shadow-2xl">
                    Belanja Sekarang
                </a>
            </div>
        </div>
    </header>

    <div id="katalog" class="bg-slate-50 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-playfair font-bold text-gray-900 mb-4">Koleksi Skincare Kami</h3>
                <div class="w-20 h-1.5 bg-pink-600 mx-auto rounded-full"></div>
                <p class="mt-6 text-gray-500 font-medium text-lg">Produk pilihan untuk hasil yang maksimal dan sehat.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                @forelse($products as $product)
                    <div
                        class="product-card group bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-pink-50 relative">
                        <span
                            class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur-sm text-pink-600 text-[10px] font-black px-4 py-1.5 rounded-full shadow-sm uppercase italic tracking-widest">
                            {{ $product->category->name }}
                        </span>

                        <a href="{{ route('product.show', $product->id) }}" class="relative h-72 block overflow-hidden">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover transition-transform duration-700">
                            <div
                                class="absolute inset-0 bg-pink-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            </div>
                        </a>

                        <div class="p-8 text-center">
                            <h4 class="text-xl font-bold text-gray-800 mb-2 truncate hover:text-pink-600 transition">
                                <a href="{{ route('product.show', $product->id) }}">
                                    {{ $product->name }}
                                </a>
                            </h4>

                            <p class="text-pink-600 font-black text-lg mb-6 tracking-tight">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>

                            <a href="{{ route('register') }}"
                                class="block w-full py-3.5 bg-gray-900 text-white text-xs font-bold uppercase tracking-[0.2em] rounded-2xl hover:bg-pink-600 transition-all duration-300 shadow-lg hover:scale-[1.02] text-center">
                                Add To Cart
                            </a>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full text-center py-20 bg-white rounded-[3rem] shadow-inner border-2 border-dashed border-pink-100">
                        <p class="text-gray-400 italic font-medium">Belum ada produk yang ditampilkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <footer class="bg-white border-t border-pink-100 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">

                <div class="col-span-1 md:col-span-1">
                    <h1 class="text-3xl font-playfair font-bold text-pink-600 mb-6 tracking-tighter">GLOWUP</h1>
                    <p class="text-gray-500 leading-relaxed mb-6">
                        Solusi kecantikan terbaik untuk kulit sehat dan bercahaya. Kami hanya menyediakan produk
                        skincare dengan bahan aktif berkualitas tinggi.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#"
                            class="w-10 h-10 bg-pink-50 text-pink-600 rounded-full flex items-center justify-center hover:bg-pink-600 hover:text-white transition">IG</a>
                        <a href="#"
                            class="w-10 h-10 bg-pink-50 text-pink-600 rounded-full flex items-center justify-center hover:bg-pink-600 hover:text-white transition">FB</a>
                        <a href="#"
                            class="w-10 h-10 bg-pink-50 text-pink-600 rounded-full flex items-center justify-center hover:bg-pink-600 hover:text-white transition">WA</a>
                    </div>
                </div>

                <div>
                    <h4 class="text-gray-900 font-bold uppercase tracking-widest text-sm mb-6">Navigasi</h4>
                    <ul class="space-y-4 text-gray-500 font-medium text-sm">
                        <li><a href="#" class="hover:text-pink-600 transition">Beranda</a></li>
                        <li><a href="#katalog" class="hover:text-pink-600 transition">Koleksi Produk</a></li>
                        <li><a href="#" class="hover:text-pink-600 transition">Promo Spesial</a></li>
                        <li><a href="#" class="hover:text-pink-600 transition">Tentang Kami</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-gray-900 font-bold uppercase tracking-widest text-sm mb-6">Bantuan</h4>
                    <ul class="space-y-4 text-gray-500 font-medium text-sm">
                        <li><a href="#" class="hover:text-pink-600 transition">Cara Pemesanan</a></li>
                        <li><a href="#" class="hover:text-pink-600 transition">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-pink-600 transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-pink-600 transition">Hubungi Kami</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-gray-900 font-bold uppercase tracking-widest text-sm mb-6">Metode Pengiriman</h4>
                    <div class="flex flex-wrap gap-3 grayscale opacity-60">
                        <span class="px-3 py-1 bg-gray-100 rounded-md font-bold text-[10px]">JNE</span>
                        <span class="px-3 py-1 bg-gray-100 rounded-md font-bold text-[10px]">SICEPAT</span>
                        <span class="px-3 py-1 bg-gray-100 rounded-md font-bold text-[10px]">J&T</span>
                        <span class="px-3 py-1 bg-gray-100 rounded-md font-bold text-[10px]">GOSEND</span>
                    </div>
                    <h4 class="text-gray-900 font-bold uppercase tracking-widest text-sm mt-8 mb-4">Newsletter</h4>
                    <div class="flex">
                        <input type="email" placeholder="Email kamu"
                            class="w-full bg-pink-50 border-none rounded-l-xl text-sm p-3 focus:ring-pink-500">
                        <button class="bg-pink-600 text-white px-4 rounded-r-xl font-bold text-xs">OK</button>
                    </div>
                </div>

            </div>

            <div class="border-t border-pink-50 pt-10 text-center">
                <p class="text-gray-400 text-xs font-medium tracking-wide">
                    &copy; 2026 GLOWUP BEAUTY. All rights reserved. <br class="md:hidden">
                    Crafted with <span class="text-pink-500">♥</span> for Skincare Enthusiasts.
                </p>
            </div>
        </div>
    </footer>

</body>

</html>
