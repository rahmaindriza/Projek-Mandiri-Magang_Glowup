<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight tracking-tighter">Logistik &
            Manajemen Stok 📦</h2>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-pink-100 h-fit">
                    <h3 class="text-xl font-black text-gray-800 mb-6 flex items-center">
                        <span class="bg-pink-600 w-2 h-8 rounded-full mr-3"></span> Update Inventaris ✨
                    </h3>

                    <form action="{{ route('admin.stock.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label
                                class="block text-[10px] font-black text-pink-600 uppercase tracking-widest mb-3">Tindakan</label>
                            <div class="flex gap-3">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="type" value="in" class="hidden peer" checked>
                                    <div
                                        class="py-3 text-center rounded-2xl border border-pink-100 text-[10px] font-black uppercase tracking-wider peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 transition-all duration-300 shadow-sm">
                                        ➕ Tambah
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="type" value="out" class="hidden peer">
                                    <div
                                        class="py-3 text-center rounded-2xl border border-pink-100 text-[10px] font-black uppercase tracking-wider peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 transition-all duration-300 shadow-sm">
                                        ➖ Kurang
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label
                                class="block text-[10px] font-black text-pink-600 uppercase tracking-widest mb-2">Pilih
                                Produk</label>
                            <select name="product_id"
                                class="w-full rounded-2xl border-pink-100 focus:ring-pink-500 text-sm font-bold text-gray-700 shadow-sm">
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}"
                                        {{ isset($selectedProductId) && $selectedProductId == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} (Sisa: {{ $p->stock }} pcs)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5">
                            <label
                                class="block text-[10px] font-black text-pink-600 uppercase tracking-widest mb-2">Jumlah
                                Unit</label>
                            <input type="number" name="qty" required
                                class="w-full rounded-2xl border-pink-100 focus:ring-pink-500 font-bold shadow-sm"
                                placeholder="Contoh: 10">
                        </div>

                        <div class="mb-8">
                            <label
                                class="block text-[10px] font-black text-pink-600 uppercase tracking-widest mb-2">Keterangan
                                Tambahan</label>
                            <textarea name="description" rows="3" class="w-full rounded-2xl border-pink-100 text-sm shadow-sm"
                                placeholder="Contoh: Barang datang dari supplier / Retur barang rusak..."></textarea>
                        </div>

                        <button type="submit"
                            class="w-full py-4 bg-pink-600 text-white font-black rounded-2xl hover:shadow-lg hover:shadow-pink-200 transition-all duration-300 uppercase tracking-widest text-[10px]">
                            Simpan Mutasi Stok 📦
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-sm border border-pink-100">
                    <h3 class="text-xl font-black text-gray-800 mb-6 flex items-center">
                        <span class="bg-pink-600 w-2 h-8 rounded-full mr-3"></span> Riwayat Mutasi Barang 🕒
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-pink-600 text-[10px] font-black uppercase border-b border-pink-50">
                                    <th class="px-4 py-4">Waktu & Admin</th>
                                    <th class="px-4 py-4">Produk</th>
                                    <th class="px-4 py-4 text-center">Mutasi</th>
                                    <th class="px-4 py-4">Posisi Stok</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 text-sm">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-pink-50/30 transition-colors">
                                        <td class="px-4 py-4">
                                            <p class="font-bold text-gray-800 text-xs">
                                                {{ $log->created_at->format('d/m/Y') }}</p>
                                            <p class="text-[9px] text-gray-400 font-medium tracking-tight">Admin:
                                                {{ $log->admin_name }}</p>
                                        </td>
                                        <td class="px-4 py-4">
                                            <p class="font-bold text-gray-700 leading-tight text-xs">
                                                {{ $log->product->name }}</p>
                                            <p class="text-[9px] text-pink-400 italic">"{{ $log->description }}"</p>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            @if ($log->qty_added > 0)
                                                <span
                                                    class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-[9px] font-black italic">+{{ $log->qty_added }}</span>
                                            @else
                                                <span
                                                    class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-[9px] font-black italic">{{ $log->qty_added }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">
                                                Awal: {{ $log->stock_before }}</p>
                                            <p class="text-[9px] text-pink-600 font-black uppercase tracking-tighter">
                                                Final: {{ $log->stock_after }}</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-12 text-center text-gray-400 text-xs italic">
                                            Belum ada riwayat mutasi stok.</td>
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
