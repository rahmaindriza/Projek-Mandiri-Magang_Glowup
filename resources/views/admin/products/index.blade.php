<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">
                {{ __('Manajemen Produk Skincare') }}
            </h2>
            <a href="{{ route('admin.products.create') }}"
                class="inline-flex items-center px-6 py-2.5 bg-pink-600 border border-transparent rounded-full font-bold text-xs text-white uppercase tracking-widest hover:bg-pink-700 shadow-lg shadow-pink-200 transition duration-300">
                + Tambah Produk Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-500 text-white rounded-2xl shadow-md border-none flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-white">
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-pink-50">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-pink-600 uppercase tracking-widest">Gambar</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-pink-600 uppercase tracking-widest">Nama Produk</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-pink-600 uppercase tracking-widest">Kategori</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-pink-600 uppercase tracking-widest">Harga</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-pink-600 uppercase tracking-widest">Stok</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-pink-600 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($products as $product)
                                    <tr class="hover:bg-pink-50/50 transition duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                class="w-16 h-16 object-cover rounded-2xl shadow-sm border border-pink-100">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-800">{{ $product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-[10px] font-black uppercase italic">
                                                {{ $product->category->name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                            {{ $product->stock }} <span class="text-xs text-gray-400">pcs</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex justify-center items-center space-x-3">
                                                {{-- Tombol Tambah Stok --}}
                                                <a href="{{ route('admin.products.addStock', $product->id) }}"
                                                   class="p-2 bg-green-50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition shadow-sm"
                                                   title="Tambah Stok">
                                                    <span class="mr-1">➕</span> Tambah Stok
                                                </a>

                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                                   class="bg-amber-50 text-amber-600 px-4 py-2 rounded-xl hover:bg-amber-600 hover:text-white transition shadow-sm flex items-center font-bold text-xs uppercase">
                                                    <span class="mr-1">✏️</span> Edit
                                                </a>

                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Yakin ingin menghapus produk ini? 🌸')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 font-black text-[10px] uppercase hover:underline">
                                                        Hapus 🗑️
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">
                                            Belum ada produk skincare. Klik "Tambah Produk Baru" untuk memulai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
