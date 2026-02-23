<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-semibold text-2xl text-pink-600 leading-tight">Konfirmasi Pesanan ✨</h2>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-white">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 font-playfair">Data Pengiriman</h3>
                    <form id="checkout-form">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-600 mb-2">Nomor WhatsApp</label>
                            <input type="text" id="phone" name="phone" placeholder="0812xxxxxxxx"
                                class="w-full rounded-2xl border-gray-100 p-4 bg-pink-50/30 focus:ring-pink-500 shadow-sm"
                                required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-600 mb-2">Alamat Lengkap</label>
                            <textarea id="address" name="address" rows="4" placeholder="Jl. Kecantikan No. 123, Jakarta..."
                                class="w-full rounded-2xl border-gray-100 p-4 bg-pink-50/30 focus:ring-pink-500 shadow-sm" required></textarea>
                        </div>

                        @foreach ($cartItems as $item)
                            <input type="hidden" name="item_ids[]" value="{{ $item->id }}">
                        @endforeach

                        <button type="button" id="pay-button"
                            class="w-full py-5 bg-pink-600 text-white font-bold rounded-2xl shadow-xl shadow-pink-100 hover:bg-pink-700 transition transform hover:scale-[1.02] active:scale-95 uppercase tracking-widest text-sm">
                            Buat Pesanan & Bayar Sekarang ✨
                        </button>
                    </form>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-white h-fit">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 font-playfair border-b border-pink-50 pb-4">
                        Ringkasan Belanja</h3>
                    <div class="space-y-4 mb-6">
                        @foreach ($cartItems as $item)
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-gray-800">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-400 italic">Jumlah: {{ $item->quantity }} pcs</p>
                                </div>
                                <span class="font-bold text-gray-700">Rp
                                    {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t-2 border-dashed border-pink-100">
                        <span class="font-bold text-gray-400 uppercase tracking-widest text-xs">Total Akhir:</span>
                        <span class="text-3xl font-black text-pink-600 italic">Rp
                            {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
    const payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function(e) {
        e.preventDefault();

        const phone = document.getElementById('phone').value;
        const address = document.getElementById('address').value;

        // Perbaikan: Ambil semua value dari input item_ids[] dengan benar
        const itemIds = [];
        document.querySelectorAll('input[name="item_ids[]"]').forEach(function(input) {
            itemIds.push(input.value);
        });

        if (!phone || !address) {
            alert('Tolong isi nomor HP dan alamat kamu ya! ✨');
            return;
        }

        // Tampilkan loading sederhana agar kamu tahu proses sedang jalan
        payButton.innerHTML = "Sedang Memproses... ⏳";
        payButton.disabled = true;

        fetch("{{ route('checkout.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    phone: phone,
                    address: address,
                    item_ids: itemIds
                })
            })
            .then(response => response.json())
            .then(data => {
                payButton.innerHTML = "Buat Pesanan & Bayar Sekarang ✨";
                payButton.disabled = false;

                if (data.snap_token) {
                    // Eksekusi pemanggilan pop-up Midtrans
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = "{{ route('dashboard') }}";
                            alert("Yeay! Pembayaran sukses. ✨");
                        },
                        onPending: function(result) {
                            alert("Selesaikan pembayaranmu di aplikasi ya!");
                        },
                        onError: function(result) {
                            alert("Duh, pembayaran gagal. Coba lagi ya!");
                        }
                    });
                } else {
                    // Ini akan memunculkan error detail jika token gagal dibuat
                    alert('Gagal: ' + (data.error || 'Terjadi kesalahan internal.'));
                }
            })
            .catch(error => {
                payButton.innerHTML = "Buat Pesanan & Bayar Sekarang ✨";
                payButton.disabled = false;
                console.error('Error:', error);
                alert('Gagal terhubung ke server. Cek koneksi internet atau Laragon kamu.');
            });
    });
</script>
</x-app-layout>
