<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-black text-3xl text-pink-600 leading-tight tracking-tighter">
            {{ __('Laporan Keuangan GlowUp ✨') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div class="bg-white p-10 rounded-[3rem] shadow-2xl shadow-pink-100 border border-white">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Omzet</p>
                    <h3 class="text-4xl font-black text-pink-600 tracking-tighter">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="bg-white p-10 rounded-[3rem] shadow-2xl shadow-pink-100 border border-white">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Transaksi Sukses</p>
                    <h3 class="text-4xl font-black text-gray-800 tracking-tighter">
                        {{ $orders->count() }} <span class="text-sm font-bold text-gray-400 uppercase ml-2">Pesanan</span>
                    </h3>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-[3rem] border border-white">
                <div class="p-10">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-pink-600 w-2 h-8 rounded-full mr-3"></span>
                            Rincian Pendapatan Masuk
                        </h3>

                        <div class="flex space-x-3">
                            <button onclick="exportToExcel('laporan-utama', 'Laporan_Keuangan_GlowUp')"
                                class="px-6 py-2 bg-green-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg hover:bg-green-600 transition-all">
                                📥 Export Excel
                            </button>
                            <button onclick="window.print()"
                                class="px-6 py-2 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg hover:bg-pink-600 transition-all">
                                🖨️ Cetak PDF
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="laporan-utama" class="w-full text-left">
                            <thead>
                                <tr class="text-pink-600 text-[10px] font-black uppercase tracking-[0.2em] border-b border-pink-50">
                                    <th class="px-6 py-4">Tanggal</th>
                                    <th class="px-6 py-4">Pelanggan</th>
                                    <th class="px-6 py-4">Nominal</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($orders as $order)
                                    <tr class="hover:bg-pink-50/30 transition-colors">
                                        <td class="px-6 py-4 text-sm font-bold text-gray-400">
                                            {{ $order->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800">{{ $order->user->name }}</p>
                                            <p class="text-[10px] text-gray-400 italic">{{ $order->user->email }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-black text-pink-600">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-4 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-green-100 text-green-600">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportToExcel(tableID, filename = '') {
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML;

            // Format agar mendukung karakter khusus (Rupiah) dan spasi
            var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'></head><body>";
            var footer = "</body></html>";
            var fullHTML = header + tableHTML + footer;

            var blob = new Blob([fullHTML], {
                type: 'application/vnd.ms-excel'
            });

            var url = URL.createObjectURL(blob);
            var downloadLink = document.createElement("a");
            downloadLink.href = url;
            downloadLink.download = filename + '.xls';

            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>
</x-app-layout>
