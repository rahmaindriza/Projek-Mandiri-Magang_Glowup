<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">
            {{ __('Katalog Produk Skincare') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto px-4 mb-10">
    <form action="{{ route('customer.katalog') }}" method="GET" class="relative group max-w-md mx-auto">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari skincare favoritmu... ✨"
            class="w-full pl-12 pr-4 py-4 rounded-full border-none shadow-lg shadow-pink-100 focus:ring-2 focus:ring-pink-500 transition-all duration-300">

        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-pink-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        @if(request('search'))
            <a href="{{ route('customer.katalog') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-600 font-bold text-xs uppercase tracking-widest">
                Reset
            </a>
        @endif
    </form>
</div>

<div class="max-w-7xl mx-auto px-4 mb-8">
    <div class="flex flex-wrap justify-center gap-3">
        <a href="{{ route('customer.katalog') }}"
           class="px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all
           {{ !request('category') ? 'bg-pink-600 text-white shadow-lg shadow-pink-200' : 'bg-white text-gray-400 hover:text-pink-600 border border-pink-50' }}">
            Semua ✨
        </a>

        @foreach($categories as $cat)
            <a href="{{ route('customer.katalog', ['category' => $cat->id, 'search' => request('search')]) }}"
               class="px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all
               {{ request('category') == $cat->id ? 'bg-pink-600 text-white shadow-lg shadow-pink-200' : 'bg-white text-gray-400 hover:text-pink-600 border border-pink-50' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>
</div>  
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($products as $product)
                    <div
                        class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-white p-4 transition-all hover:shadow-xl group">

                        <a href="{{ route('product.show', $product->id) }}"
                            class="block relative h-64 overflow-hidden rounded-[2rem] mb-6">
                            <img src="{{ asset('storage/' . $product->image) }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-pink-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            </div>
                        </a>

                        <div class="px-2 text-center">
                            <span
                                class="text-[10px] font-black text-pink-600 uppercase tracking-widest italic">{{ $product->category->name }}</span>

                            <h4 class="text-xl font-bold text-gray-800 mt-1 mb-2 hover:text-pink-600 transition">
                                <a href="{{ route('product.show', $product->id) }}">
                                    {{ $product->name }}
                                </a>
                            </h4>

                            <p class="text-pink-600 font-black text-lg mb-4">Rp
                                {{ number_format($product->price, 0, ',', '.') }}</p>

                            <div class="px-2 text-center">
                                <form action="{{ route('cart.store', $product->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="redirect_to" value="index">
                                    <button type="submit"
                                        class="w-full bg-gray-900 text-white py-3 rounded-2xl text-xs font-bold uppercase tracking-widest hover:bg-pink-600 transition duration-300">
                                        + Tambah Ke Keranjang
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-pink-100">
                        <p class="text-gray-400 italic font-medium tracking-tight">Belum ada produk skincare yang
                            tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
