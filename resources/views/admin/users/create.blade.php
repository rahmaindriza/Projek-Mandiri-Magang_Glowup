<x-app-layout>
    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-pink-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <span class="bg-pink-600 w-2 h-8 rounded-full mr-3"></span>
                    Tambah User Baru 👤
                </h3>

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-xs font-black uppercase mb-2">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full border-pink-100 rounded-2xl focus:ring-pink-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-xs font-black uppercase mb-2">Email</label>
                        <input type="email" name="email" class="w-full border-pink-100 rounded-2xl focus:ring-pink-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-xs font-black uppercase mb-2">Pilih Role</label>
                        <select name="role" class="w-full border-pink-100 rounded-2xl focus:ring-pink-500 bg-white" required>
                            <option value="customer">Customer (Pelanggan Biasa)</option>
                            <option value="admin">Admin (Pengelola Toko)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-xs font-black uppercase mb-2">Password</label>
                        <input type="password" name="password" class="w-full border-pink-100 rounded-2xl focus:ring-pink-500" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-xs font-black uppercase mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="w-full border-pink-100 rounded-2xl focus:ring-pink-500" required>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="bg-pink-600 text-white px-8 py-2 rounded-full font-black text-[10px] uppercase tracking-widest shadow-lg hover:bg-pink-700 transition">
                            Simpan User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-600 px-8 py-2 rounded-full font-black text-[10px] uppercase tracking-widest">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
