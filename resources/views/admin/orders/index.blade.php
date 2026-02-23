<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">
            {{ __('Daftar Pesanan Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-[2.5rem] border border-white p-8">

                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-xl font-bold text-gray-800">Semua Transaksi Pelanggan</h3>
                    <div
                        class="bg-pink-100 text-pink-600 px-4 py-2 rounded-2xl text-xs font-black uppercase tracking-widest">
                        Total: {{ $orders->count() }} Pesanan
                    </div>
                </div>

                <div class="overflow-x-auto">

                    @if (session('success'))
                        <div
                            class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl text-xs font-bold">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-pink-600 text-[10px] font-black uppercase tracking-[0.2em] border-b border-pink-50">
                                <th class="px-4 py-4 text-center">ID</th>
                                <th class="px-4 py-4">Nama Pembeli</th>
                                <th class="px-4 py-4 text-right">Total Bayar</th>
                                <th class="px-4 py-4 text-center">Status</th>
                                <th class="px-4 py-4">Alamat Pengiriman</th>
                                <th class="px-4 py-4 text-center">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($orders as $order)
                                <tr class="hover:bg-pink-50/30 transition-colors">
                                    <td class="px-4 py-6 text-center font-bold text-gray-400">#{{ $order->id }}</td>
                                    <td class="px-4 py-6">
                                        <p class="font-bold text-gray-800">{{ $order->user->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium">{{ $order->user->email }}</p>
                                    </td>
                                    <td class="px-4 py-6 text-right font-black text-pink-600">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-6 text-center">
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}"
                                            method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()"
                                                class="text-[10px] font-black uppercase tracking-widest border-none rounded-full px-4 py-1.5 cursor-pointer
            {{ $order->status == 'success' ? 'bg-green-100 text-green-600' : '' }}
            {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-600' : '' }}
            {{ $order->status == 'dikirim' ? 'bg-blue-100 text-blue-600' : '' }}
            {{ $order->status == 'selesai' ? 'bg-purple-100 text-purple-600' : '' }}
            {{ $order->status == 'dibatalkan' ? 'bg-red-100 text-red-600' : '' }}">

                                                <option value="pending"
                                                    {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="success"
                                                    {{ $order->status == 'success' ? 'selected' : '' }}>Success (Lunas)
                                                </option>
                                                <option value="dikirim"
                                                    {{ $order->status == 'dikirim' ? 'selected' : '' }}>Dikirim 🚚
                                                </option>
                                                <option value="selesai"
                                                    {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai ✅
                                                </option>
                                                <option value="dibatalkan"
                                                    {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan ❌
                                                </option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-4 py-6">
                                        <p class="text-xs text-gray-600 font-medium truncate max-w-[200px]"
                                            title="{{ $order->address }}">
                                            📍 {{ $order->address }}
                                        </p>
                                    </td>
                                    <td class="px-4 py-6 text-center text-xs text-gray-400 font-bold">
                                        {{ $order->created_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-20 text-center">
                                        <div class="text-5xl mb-4">📦</div>
                                        <p class="text-gray-400 italic">Belum ada pesanan masuk hari ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
