<x-app-layout>
    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-500 text-white rounded-2xl shadow-lg border-none flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-pink-100 p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="bg-pink-600 w-2 h-8 rounded-full mr-3"></span>
                        Kelola User GlowUp ✨
                    </h3>

                    <a href="{{ route('admin.users.create') }}"
                       class="bg-pink-600 text-white px-6 py-2 rounded-full font-black text-[10px] uppercase tracking-widest shadow-lg hover:bg-pink-700 transition">
                        + Tambah User Baru
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-pink-600 text-[10px] font-black uppercase tracking-widest border-b border-pink-50">
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3 text-center">Role</th> <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($users as $user)
                            <tr class="hover:bg-pink-50/30 transition-colors">
                                <td class="px-4 py-4 text-sm font-bold text-gray-800">{{ $user->name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-500 lowercase italic">{{ $user->email }}</td>

                                <td class="px-4 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                        {{ $user->role == 'admin' ? 'bg-pink-100 text-pink-600' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $user->role ?? 'customer' }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex justify-center items-center space-x-6">
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="text-yellow-600 font-black text-[10px] uppercase tracking-widest hover:underline">
                                            Edit ✏️
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 font-black text-[10px] uppercase tracking-widest hover:underline">
                                                Hapus 🗑️
                                            </button>
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
    </div>
</x-app-layout>
