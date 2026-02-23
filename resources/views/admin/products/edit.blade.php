<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">
            {{ __('Edit Produk: ') }} {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-white p-8">

                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Skincare</label>
                            <input type="text" name="name" value="{{ $product->name }}" required class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                            <select name="category_id" required class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp)</label>
                            <input type="number" name="price" value="{{ $product->price }}" required class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Stok</label>
                            <input type="number" name="stock" value="{{ $product->stock }}" required class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500">{{ $product->description }}</textarea>
                    </div>

                    <div class="bg-pink-50 p-6 rounded-2xl border-2 border-dashed border-pink-200">
                        <label class="block text-sm font-bold text-pink-700 mb-2 text-center">Update Foto (Biarkan kosong jika tidak ingin diganti)</label>
                        <div class="flex items-center justify-center space-x-6">
                            <div class="text-center">
                                <p class="text-[10px] uppercase text-gray-400 mb-2">Foto Saat Ini</p>
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-24 h-24 object-cover rounded-xl border-2 border-white shadow-sm">
                            </div>
                            <input type="file" name="image" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-pink-600 file:text-white">
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <a href="{{ route('admin.products.index') }}" class="w-1/3 py-4 bg-gray-100 text-gray-600 text-center font-bold rounded-2xl hover:bg-gray-200 transition">
                            Batal
                        </a>
                        <button type="submit" class="w-2/3 py-4 bg-pink-600 text-white font-bold rounded-2xl shadow-xl shadow-pink-100 hover:bg-pink-700 hover:scale-[1.01] transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
