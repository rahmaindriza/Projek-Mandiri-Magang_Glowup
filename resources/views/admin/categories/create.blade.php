<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">
            {{ __('Tambah Kategori Baru') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-white">
                <h3 class="font-bold text-gray-800 mb-6 text-lg flex items-center">
                    <span class="mr-2">🏷️</span> Kategori Skincare Baru
                </h3>

                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest text-[10px] text-pink-600">Nama Kategori</label>
                        <input type="text" name="name"
                               class="w-full rounded-2xl border-gray-100 bg-pink-50/30 focus:border-pink-500 focus:ring-pink-500 transition-all p-4"
                               placeholder="Contoh: Face Mist, Masker, dll"
                               required>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('admin.categories.index') }}"
                           class="w-1/2 py-4 bg-gray-100 text-gray-600 text-center font-bold rounded-2xl hover:bg-gray-200 transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="w-1/2 py-4 bg-pink-600 text-white font-bold rounded-2xl shadow-lg shadow-pink-100 hover:bg-pink-700 transition">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
