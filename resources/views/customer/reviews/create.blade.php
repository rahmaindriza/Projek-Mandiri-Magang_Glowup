<x-app-layout>
    <div class="py-12 bg-pink-50/50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-10 rounded-[3rem] shadow-2xl border border-white text-center">
                <h2 class="font-playfair font-black text-2xl text-pink-600 mb-2">Berikan Ulasan Cantikmu ✨</h2>
                <p class="text-gray-400 text-sm mb-8 italic">Untuk produk: {{ $product->name }}</p>

                    <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-[0.2em] mb-2 block">Rating
                            Produk</label>
                        <select name="rating"
                            class="w-full rounded-2xl border-pink-100 text-pink-600 focus:ring-pink-500 font-bold py-4">
                            <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                            <option value="4">⭐⭐⭐⭐ (Puas)</option>
                            <option value="3">⭐⭐⭐ (Cukup)</option>
                            <option value="2">⭐⭐ (Kurang)</option>
                            <option value="1">⭐ (Kecewa)</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="text-[10px] font-black uppercase text-gray-400 tracking-[0.2em] mb-2 block">Ceritakan
                            Pengalamanmu</label>
                        <textarea name="comment" rows="4" required
                            class="w-full rounded-[1.5rem] border-pink-100 focus:ring-pink-500 italic text-sm"
                            placeholder="Bagaimana kesanmu setelah menggunakan produk ini?"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full py-4 bg-pink-600 text-white font-black rounded-2xl shadow-lg hover:bg-pink-700 transition-all uppercase tracking-widest text-xs">
                        Kirim Ulasan Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
