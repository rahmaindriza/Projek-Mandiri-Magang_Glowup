<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-playfair font-black text-3xl text-pink-600 leading-tight tracking-tighter">
                {{ __('Tambah Koleksi GlowUp ✨') }}
            </h2>
            <a href="{{ route('admin.products.index') }}" class="text-[10px] font-black text-gray-400 hover:text-pink-600 transition uppercase tracking-[0.2em]">
                ← Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-pink-50/30 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur-sm rounded-[3rem] shadow-2xl shadow-pink-100 border border-white overflow-hidden">
                <div class="p-10">
                    <div class="flex items-center mb-10 border-b border-pink-50 pb-6">
                        <span class="bg-pink-600 w-3 h-10 rounded-full mr-4 shadow-lg shadow-pink-200"></span>
                        <h3 class="text-2xl font-black text-gray-800 tracking-tight">Informasi Produk Baru</h3>
                    </div>

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="group">
                                <label class="block text-[10px] font-black text-pink-600 uppercase tracking-widest mb-3 ml-2">Nama Produk Skincare</label>
                                <input type="text" name="name" required
                                    class="w-full rounded-[1.5rem] border-gray-100 bg-pink-50/30 focus:bg-white focus:border-pink-500 focus:ring-pink-500 transition-all duration-300 placeholder-gray-300"
                                    placeholder="Contoh: Glow Facial Wash">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-pink-600 uppercase tracking-widest mb-3 ml-2">Pilih Kategori</label>
                                <select name="category_id" required
                                    class="w-full rounded-[1.5rem] border-gray-100 bg-pink-50/30 focus:bg-white focus:border-pink-500 focus:ring-pink-500 transition-all duration-300 text-gray-600">
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-pink-600 uppercase tracking-widest mb-3 ml-2">Harga Jual (Rp)</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                                    <input type="number" name="price" required
                                        class="w-full pl-12 rounded-[1.5rem] border-gray-100 bg-pink-50/30 focus:bg-white focus:border-pink-500 focus:ring-pink-500 transition-all placeholder-gray-300"
                                        placeholder="0">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-pink-600 uppercase tracking-widest mb-3 ml-2">Jumlah Stok (Pcs)</label>
                                <input type="number" name="stock" required
                                    class="w-full rounded-[1.5rem] border-gray-100 bg-pink-50/30 focus:bg-white focus:border-pink-500 focus:ring-pink-500 transition-all placeholder-gray-300"
                                    placeholder="0">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-pink-600 uppercase tracking-widest mb-3 ml-2">Deskripsi & Manfaat Produk</label>
                            <textarea name="description" rows="4"
                                class="w-full rounded-[2rem] border-gray-100 bg-pink-50/30 focus:bg-white focus:border-pink-500 focus:ring-pink-500 transition-all placeholder-gray-300"
                                placeholder="Jelaskan kandungan dan keunggulan produk ini..."></textarea>
                        </div>

                        <div class="relative group">
                            <label class="block text-[10px] font-black text-pink-600 uppercase tracking-widest mb-3 ml-2">Visual Produk (Foto)</label>
                            <div class="bg-pink-50/50 p-10 rounded-[2rem] border-2 border-dashed border-pink-200 group-hover:border-pink-500 transition-all duration-500 text-center">
                                <input type="file" name="image" id="imageInput" required
                                    class="hidden" onchange="previewImage()">
                                <label for="imageInput" class="cursor-pointer">
                                    <div class="mb-4 text-4xl">📸</div>
                                    <p class="text-xs font-black text-pink-600 uppercase tracking-widest mb-1">Klik untuk Upload Gambar</p>
                                    <p class="text-[9px] text-gray-400 font-bold">Format: JPG, PNG, WEBP (Maks. 2MB)</p>
                                </label>
                                <img id="imagePreview" class="mt-6 mx-auto rounded-2xl shadow-lg max-h-48 hidden">
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit"
                                class="w-full py-5 bg-gray-900 text-white font-black rounded-[1.5rem] shadow-xl shadow-gray-200 hover:bg-pink-600 hover:scale-[1.02] active:scale-95 transition-all duration-500 uppercase tracking-[0.3em] text-[10px]">
                                Simpan Produk ke Galeri ✨
                            </button>
                        </div>
                    </form> </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage() {
            const input = document.getElementById('imageInput');
            const preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
