
<x-app-layout>
    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-pink-100">
                <h3 class="text-2xl font-playfair font-black text-pink-600 mb-6">Tambah Stok Skincare ✨</h3>

                <div class="flex items-center mb-8 p-4 bg-pink-50 rounded-2xl">
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-20 h-20 object-cover rounded-xl mr-4">
                    <div>
                        <p class="font-bold text-gray-800">{{ $product->name }}</p>
                        <p class="text-sm text-pink-600">Stok Saat Ini: {{ $product->stock }} pcs</p>
                    </div>
                </div>

                <form action="{{ route('admin.products.updateStock', $product->id) }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Stok Masuk</label>
                        <input type="number" name="qty" class="w-full rounded-2xl border-pink-100 focus:ring-pink-500" placeholder="Contoh: 50" required>
                    </div>
                    <button type="submit" class="w-full py-4 bg-pink-600 text-white font-bold rounded-2xl shadow-lg hover:bg-pink-700 transition">
                        Simpan Perubahan Stok
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
