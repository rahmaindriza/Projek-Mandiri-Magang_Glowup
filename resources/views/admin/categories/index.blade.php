<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">
                {{ __('Daftar Kategori Skincare') }}
            </h2>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-6 py-2.5 bg-pink-600 text-white rounded-full font-bold text-xs uppercase tracking-widest hover:bg-pink-700 shadow-lg transition">
                + Tambah Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-500 text-white rounded-2xl shadow-md border-none flex items-center">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-white p-8">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-pink-50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-pink-600 uppercase tracking-widest">Nama Kategori</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-pink-600 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($categories as $cat)
                        <tr class="hover:bg-pink-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $cat->name }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.categories.edit', $cat->id) }}" class="p-2 bg-amber-100 text-amber-600 rounded-xl hover:bg-amber-600 hover:text-white transition">✏️ Edit</a>

                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="p-2 bg-rose-100 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
