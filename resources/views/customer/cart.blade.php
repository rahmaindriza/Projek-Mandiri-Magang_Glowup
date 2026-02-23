<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">
            {{ __('Keranjang Belanja Saya') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('checkout.index') }}" method="GET">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-[2.5rem] border border-white p-8">

                    @if ($cartItems->count() > 0)
                        <div class="flex items-center mb-6 px-4 py-3 bg-pink-50/50 rounded-2xl border border-pink-100">
                            <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)"
                                   class="w-5 h-5 rounded text-pink-600 focus:ring-pink-500 border-pink-200">
                            <label for="select-all" class="ml-3 text-sm font-bold text-gray-700 uppercase tracking-widest cursor-pointer">
                                Pilih Semua Produk
                            </label>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full mb-8">
                                <thead>
                                    <tr class="text-left border-b border-pink-50 text-pink-600 uppercase text-xs font-bold tracking-widest">
                                        <th class="px-4 py-4">Produk</th>
                                        <th class="px-4 py-4 text-center">Jumlah</th>
                                        <th class="px-4 py-4 text-right">Harga</th>
                                        <th class="px-4 py-4 text-right">Subtotal</th>
                                        <th class="px-4 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($cartItems as $item)
                                        <tr class="hover:bg-pink-50/30 transition-colors">
                                            <td class="px-4 py-6 flex items-center">
                                                <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
                                                       class="item-checkbox w-5 h-5 rounded text-pink-600 focus:ring-pink-500 border-pink-200 mr-4">

                                                <a href="{{ route('product.show', $item->product->id) }}" class="group flex items-center">
                                                    <div class="relative overflow-hidden rounded-2xl shadow-sm mr-4">
                                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                                             class="w-20 h-20 object-cover group-hover:scale-110 transition duration-500">
                                                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-gray-800 group-hover:text-pink-600 transition">{{ $item->product->name }}</p>
                                                        <p class="text-[10px] text-pink-500 font-bold uppercase italic tracking-tighter">
                                                            {{ $item->product->category->name }}
                                                        </p>
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="px-4 py-6 text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <button type="submit" form="update-form-{{ $item->id }}"
                                                            class="w-7 h-7 flex items-center justify-center bg-gray-100 text-gray-600 rounded-full hover:bg-pink-100 hover:text-pink-600 transition font-bold"
                                                            {{ $item->quantity <= 1 ? 'disabled opacity-50' : '' }}>-</button>

                                                    <span class="text-lg font-black text-gray-800 w-8">{{ $item->quantity }}</span>

                                                    <button type="submit" form="add-form-{{ $item->id }}"
                                                            class="w-7 h-7 flex items-center justify-center bg-gray-900 text-white rounded-full hover:bg-pink-600 transition font-bold">+</button>
                                                </div>
                                            </td>
                                            <td class="px-4 py-6 text-right text-gray-600">
                                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-6 text-right font-bold text-pink-600">
                                                Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-6 text-center">
                                                <button type="button" onclick="confirmDelete({{ $item->id }})"
                                                        class="text-rose-400 hover:text-rose-600 transition hover:scale-125">
                                                    🗑️
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex flex-col md:flex-row justify-between items-center bg-pink-50/50 p-8 rounded-[2rem] border border-pink-100">
                            <div class="mb-4 md:mb-0 text-center md:text-left">
                                <p class="text-gray-500 font-medium">Klik tombol di samping untuk :</p>
                                <h3 class="text-xl font-black text-gray-900 tracking-tight">Proses Produk Terpilih ✨</h3>
                            </div>
                            <button type="submit"
                                class="px-12 py-4 bg-pink-600 text-white font-bold rounded-2xl shadow-xl shadow-pink-100 hover:bg-pink-700 transition transform hover:scale-105 active:scale-95">
                                Lanjut ke Checkout 🛍️
                            </button>
                        </div>
                    @else
                        <div class="text-center py-20">
                            <div class="text-6xl mb-6">🛒</div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Keranjangmu masih kosong</h3>
                            <p class="text-gray-400 mb-8 italic">Yuk, cari skincare favoritmu sekarang!</p>
                            <a href="{{ route('customer.katalog') }}"
                                class="px-10 py-4 bg-pink-600 text-white font-bold rounded-full">Mulai Belanja</a>
                        </div>
                    @endif

                </div>
            </form>

            @foreach ($cartItems as $item)
                <form id="update-form-{{ $item->id }}" action="{{ route('cart.update', $item->id) }}" method="POST" class="hidden">
                    @csrf @method('PATCH')
                    <input type="hidden" name="action" value="decrease">
                </form>
                <form id="add-form-{{ $item->id }}" action="{{ route('cart.update', $item->id) }}" method="POST" class="hidden">
                    @csrf @method('PATCH')
                    <input type="hidden" name="action" value="increase">
                </form>
                <form id="delete-form-{{ $item->id }}" action="{{ route('cart.destroy', $item->id) }}" method="POST" class="hidden">
                    @csrf @method('DELETE')
                </form>
            @endforeach
        </div>
    </div>

    <script>
        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }

        function confirmDelete(id) {
            if(confirm('Hapus produk ini dari keranjang?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
</x-app-layout>
