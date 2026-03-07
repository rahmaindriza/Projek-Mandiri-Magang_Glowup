<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight tracking-tighter">Detail Produk Skincare 🧴</h2>
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2 bg-gray-100 text-gray-600 rounded-2xl text-xs font-bold hover:bg-pink-100 transition">Kembali</a>
        </div>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-pink-100 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-pink-200 to-rose-200 rounded-[2rem] blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                        <img src="{{ asset('storage/' . $product->image) }}" class="relative rounded-[2rem] w-full h-96 object-cover shadow-sm border border-pink-50" alt="{{ $product->name }}">
                    </div>

                    <div class="flex flex-col justify-center">
                        <span class="inline-block px-4 py-1 bg-pink-100 text-pink-600 rounded-full text-[10px] font-black uppercase tracking-widest mb-4 w-fit">
                            {{ $product->category->name }}
                        </span>

                        <h1 class="text-3xl font-playfair font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
                        <p class="text-2xl font-black text-pink-600 mb-6">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="bg-pink-50/50 p-4 rounded-2xl border border-pink-50">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter mb-1">ID Produk</p>
                                <p class="text-sm font-bold text-gray-700">#{{ $product->id }}</p>
                            </div>
                            <div class="bg-pink-50/50 p-4 rounded-2xl border border-pink-50">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter mb-1">Stok Saat Ini</p>
                                <p class="text-sm font-bold {{ $product->stock < 5 ? 'text-red-500' : 'text-green-600' }}">
                                    {{ $product->stock }} pcs
                                </p>
                            </div>
                        </div>

                        <div class="mb-8">
                            <p class="text-[10px] font-black text-pink-600 uppercase tracking-widest mb-3">Deskripsi Produk</p>
                            <p class="text-gray-500 text-sm leading-relaxed italic">
                                "{{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}"
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="flex-1 py-4 bg-gray-800 text-white text-center font-black rounded-2xl hover:bg-gray-900 transition text-[10px] uppercase tracking-widest">Edit Produk</a>
                            <a href="{{ route('admin.stock.index', ['product_id' => $product->id]) }}" class="flex-1 py-4 bg-pink-600 text-white text-center font-black rounded-2xl hover:shadow-lg hover:shadow-pink-200 transition text-[10px] uppercase tracking-widest">Update Stok</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
