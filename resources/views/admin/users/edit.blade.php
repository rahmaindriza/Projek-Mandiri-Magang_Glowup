<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Edit Data User') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-pink-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <span class="bg-pink-600 w-2 h-8 rounded-full mr-3"></span>
                    Ubah Data Pelanggan 🌸
                </h3>

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 text-xs font-black uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" required
                               class="w-full border-pink-100 rounded-2xl focus:ring-pink-500 focus:border-pink-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-xs font-black uppercase tracking-widest mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" required
                               class="w-full border-pink-100 rounded-2xl focus:ring-pink-500 focus:border-pink-500">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-xs font-black uppercase tracking-widest mb-2">Tingkatan / Role</label>
                        <select name="role" required
                                class="w-full border-pink-100 rounded-2xl focus:ring-pink-500 focus:border-pink-500 appearance-none bg-white">
                            <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer (Pelanggan)</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin (Administrator)</option>
                        </select>
                        <p class="text-[9px] text-gray-400 mt-2 italic">* Pilih Admin jika ingin memberikan akses ke panel manajemen.</p>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="bg-pink-600 text-white px-8 py-2 rounded-full font-black text-[10px] uppercase tracking-widest shadow-lg hover:bg-pink-700 transition">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-600 px-8 py-2 rounded-full font-black text-[10px] uppercase tracking-widest hover:bg-gray-300 transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
