<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - GlowUp</title>
    <link href="https://fonts.bunny.net/css?family=playfair+display:700|figtree:400,500,600&display=swap"
        rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-playfair {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body class="bg-pink-50/30 font-['Figtree'] antialiased">

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-pink-100">
        <div class="max-w-7xl mx-auto px-4 h-20 flex justify-between items-center">
            <a href="{{ route('dashboard') }}"
                class="text-2xl font-playfair font-bold text-pink-600 tracking-tighter">GLOWUP</a>

            <a href="{{ Auth::user()->role == 'admin' ? route('admin.products.index') : route('customer.katalog') }}"
                class="text-sm font-bold text-gray-500 hover:text-pink-600 transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali Belanja
            </a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-16">
        <div
            class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white p-8 md:p-12 rounded-[3rem] shadow-2xl border border-white">

            <div class="rounded-[3rem] overflow-hidden shadow-2xl h-[500px]">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                    class="w-full h-full object-cover hover:scale-105 transition duration-700">
            </div>

            <div class="flex flex-col justify-center">
                <span
                    class="text-pink-600 font-bold tracking-widest uppercase text-xs mb-2 bg-pink-50 w-fit px-4 py-1 rounded-full italic">
                    {{ $product->category->name }}
                </span>

                <h1 class="text-4xl md:text-5xl font-playfair font-black text-gray-900 mb-4">{{ $product->name }}</h1>

                <p class="text-3xl font-light text-pink-500 mb-6 font-playfair italic">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

                <div class="text-gray-500 leading-relaxed mb-8 border-t border-gray-50 pt-6">
                    <h4 class="font-bold text-gray-800 mb-2 uppercase text-xs tracking-widest">Deskripsi Produk:</h4>
                    <p class="text-lg">
                        {{ $product->description ?: 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                    <div class="mt-4 text-sm font-bold text-gray-400">
                        Stok Tersedia: <span class="text-pink-600">{{ $product->stock }} pcs</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4 mt-10">

                    <form action="{{ route('cart.store', $product->id) }}" method="POST" class="flex-none">
                        @csrf
                        <input type="hidden" name="redirect_to" value="stay">

                        <button type="submit"
                            class="w-16 py-4 border-2 border-gray-900 rounded-2xl hover:bg-gray-900 hover:text-white transition flex items-center justify-center group shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-110 transition"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </button>
                    </form>
                    <form action="{{ route('cart.store', $product->id) }}" method="POST" class="flex-grow">
                        @csrf
                        <input type="hidden" name="redirect_to" value="checkout">
                        <button type="submit" class="w-full py-4 bg-pink-600 text-white font-bold rounded-2xl ...">
                            <span>Beli Sekarang ✨</span>
                        </button>
                    </form>

                </div>

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
                color: '#db2777', // Warna pink GlowUp
                iconColor: '#db2777'
            });
        </script>
    @endif

</body>

</html>
