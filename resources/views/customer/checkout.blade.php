<x-app-layout>
    <x-slot name="header">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

        <h2 class="font-semibold text-2xl text-pink-600 leading-tight">Konfirmasi Pesanan ✨</h2>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- FORM INFORMASI PENGIRIMAN --}}
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-white">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 italic">Informasi Pengiriman</h3>
                    <form id="checkout-form">
                        @csrf

                        {{-- Input Hidden untuk Beli Sekarang (Direct Checkout) ✨ --}}
                        <input type="hidden" name="product_id" value="{{ $cartItems->first()->product->id }}">
                        <input type="hidden" id="final-qty-input" name="qty"
                            value="{{ $cartItems->first()->quantity }}">

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-600 mb-2">Nomor WhatsApp</label>
                            <input type="text" id="phone" name="phone" placeholder="0812xxxxxxxx"
                                class="w-full rounded-2xl border-gray-100 p-4 bg-pink-50/30 focus:ring-pink-500 shadow-sm"
                                required>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">PROVINSI</label>
                                <select id="province" name="province"
                                    class="select2-area w-full rounded-xl border-gray-100" required>
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">KOTA/KABUPATEN</label>
                                <select id="city" name="city"
                                    class="select2-area w-full rounded-xl border-gray-100" required disabled>
                                    <option value="">Pilih Kota</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">KECAMATAN</label>
                                <select id="district" name="district"
                                    class="select2-area w-full rounded-xl border-gray-100" required disabled>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">KELURAHAN/DESA</label>
                                <select id="village" name="village"
                                    class="select2-area w-full rounded-xl border-gray-100" required disabled>
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Kode Pos</label>
                            <input type="text" id="postal_code" name="postal_code"
                                class="w-full rounded-xl border-gray-100 p-3 bg-pink-50/30" required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-600 mb-2">Alamat Lengkap</label>
                            <textarea id="address" name="address" rows="3" placeholder="Jl. Mawar No. 10..."
                                class="w-full rounded-2xl border-gray-100 p-4 bg-pink-50/30 focus:ring-pink-500 shadow-sm" required></textarea>
                        </div>

                        <div class="mb-8 p-4 bg-pink-50/50 rounded-3xl border border-pink-100">
                            <label class="block text-sm font-bold text-pink-700 mb-3 uppercase tracking-wider">Metode
                                Pembayaran</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label
                                    class="relative flex flex-col items-center p-4 border-2 bg-white rounded-2xl cursor-pointer hover:border-pink-500 transition shadow-sm">
                                    <input type="radio" name="payment_method" value="transfer"
                                        class="absolute top-2 right-2 text-pink-600" checked>
                                    <span class="text-sm font-bold text-gray-700">TRANSFER</span>
                                    <span class="text-[10px] text-gray-400">MIDTRANS</span>
                                </label>
                                <label
                                    class="relative flex flex-col items-center p-4 border-2 bg-white rounded-2xl cursor-pointer hover:border-pink-500 transition shadow-sm">
                                    <input type="radio" name="payment_method" value="cod"
                                        class="absolute top-2 right-2 text-pink-600">
                                    <span class="text-sm font-bold text-gray-700">COD</span>
                                    <span class="text-[10px] text-gray-400">BAYAR DI TEMPAT</span>
                                </label>
                            </div>
                        </div>

                        @foreach ($cartItems as $item)
                            <input type="hidden" name="item_ids[]" value="{{ $item->id }}">
                        @endforeach

                        <button type="button" id="pay-button"
                            class="w-full py-5 bg-pink-600 text-white font-bold rounded-2xl shadow-xl shadow-pink-200 hover:bg-pink-700 transition transform hover:scale-[1.02] uppercase tracking-widest text-sm">
                            Buat Pesanan Sekarang ✨
                        </button>
                    </form>
                </div>

                {{-- RINGKASAN BELANJA --}}
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-white h-fit sticky top-24">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 border-b border-pink-50 pb-4">Ringkasan Belanja</h3>
                    <div class="space-y-4 mb-6">
                        @foreach ($cartItems as $item)
                            <div class="border-b border-pink-50 pb-4">
                                <p class="font-bold text-gray-800">{{ $item->product->name }}</p>
                                <div class="flex justify-between items-center mt-2">
                                    {{-- Kontrol Qty --}}
                                    <div class="flex items-center gap-4 bg-pink-50 px-3 py-1 rounded-xl">
                                        <button type="button" onclick="updateQtyDirect(-1)"
                                            class="font-bold text-pink-600 text-xl">-</button>
                                        <span id="qty-display"
                                            class="font-black text-gray-800">{{ $item->quantity }}</span>
                                        <button type="button" onclick="updateQtyDirect(1)"
                                            class="font-bold text-pink-600 text-xl">+</button>
                                    </div>
                                    <span class="font-bold text-gray-700" id="item-price-display">
                                        Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t-2 border-dashed border-pink-100">
                        <span class="font-bold text-gray-400 uppercase tracking-widest text-xs">Total Bayar:</span>
                        <span class="text-3xl font-black text-pink-600 italic" id="total-display">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 1. Inisialisasi Data dari PHP ke JS ✨
        let currentQty = parseInt("{{ $cartItems->first()->quantity }}") || 1;
        let productPrice = parseInt("{{ $cartItems->first()->product->price }}") || 0;

        // 2. Fungsi Tambah/Kurang Barang ✨
        function updateQtyDirect(change) {
            let newQty = currentQty + change;
            if (newQty < 1) return;

            currentQty = newQty;
            document.getElementById('qty-display').innerText = newQty;
            document.getElementById('final-qty-input').value = newQty;

            let total = newQty * productPrice;
            let formattedTotal = 'Rp ' + total.toLocaleString('id-ID');

            document.getElementById('item-price-display').innerText = formattedTotal;
            document.getElementById('total-display').innerText = formattedTotal;
        }

        $(document).ready(function() {
            $('.select2-area').select2();

            // API Wilayah Indonesia
            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json`)
                .then(response => response.json())
                .then(provinces => {
                    let options = '<option value="">Pilih Provinsi</option>';
                    provinces.forEach(p => options +=
                        `<option data-id="${p.id}" value="${p.name}">${p.name}</option>`);
                    $('#province').html(options);
                });

            $('#province').on('change', function() {
                let provinceId = $(this).find(':selected').data('id');
                $('#city').prop('disabled', false).html('<option value="">Memuat...</option>');
                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
                    .then(response => response.json())
                    .then(regencies => {
                        let options = '<option value="">Pilih Kota</option>';
                        regencies.forEach(r => options +=
                            `<option data-id="${r.id}" value="${r.name}">${r.name}</option>`);
                        $('#city').html(options);
                    });
            });

            $('#city').on('change', function() {
                let cityId = $(this).find(':selected').data('id');
                $('#district').prop('disabled', false).html('<option value="">Memuat...</option>');
                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${cityId}.json`)
                    .then(response => response.json())
                    .then(districts => {
                        let options = '<option value="">Pilih Kecamatan</option>';
                        districts.forEach(d => options +=
                            `<option data-id="${d.id}" value="${d.name}">${d.name}</option>`);
                        $('#district').html(options);
                    });
            });

            $('#district').on('change', function() {
                let districtId = $(this).find(':selected').data('id');
                $('#village').prop('disabled', false).html('<option value="">Memuat...</option>');
                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`)
                    .then(response => response.json())
                    .then(villages => {
                        let options = '<option value="">Pilih Kelurahan</option>';
                        villages.forEach(v => options +=
                            `<option value="${v.name}">${v.name}</option>`);
                        $('#village').html(options);
                    });
            });

            // --- PROSES SUBMIT PESANAN ✨ ---
            const payButton = document.getElementById('pay-button');

            payButton.addEventListener('click', function(e) {
                e.preventDefault();

                const formData = {
                    phone: $('#phone').val(),
                    province: $('#province').val(),
                    city: $('#city').val(),
                    district: $('#district').val(),
                    village: $('#village').val(),
                    postal_code: $('#postal_code').val(),
                    address: $('#address').val(),
                    product_id: $('input[name="product_id"]').val(),
                    qty: currentQty,
                    payment_method: $('input[name="payment_method"]:checked').val(),
                    item_ids: Array.from(document.querySelectorAll('input[name="item_ids[]"]')).map(i =>
                        i.value)
                };

                if (!formData.phone || !formData.province || !formData.address) {
                    alert('Lengkapi alamat dan nomor WhatsApp dulu ya Cantik! ✨');
                    return;
                }

                payButton.innerHTML = "Memproses Pesanan... ⏳";
                payButton.disabled = true;

                fetch("{{ route('checkout.process') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json())
                    .then(res => {
                        if (res.snap_token) {
                            window.snap.pay(res.snap_token, {
                                onSuccess: function(result) {
                                    let orderId = result.order_id.split('-')[1];
                                    fetch(`/orders/${orderId}/update-status-manual`, {
                                        method: "POST",
                                        headers: {
                                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                        }
                                    }).then(() => {
                                        window.location.href = "{{ route('customer.orders.index') }}";
                                    });
                                },
                                onPending: function(result) {
                                    window.location.href = "{{ route('customer.orders.index') }}";
                                },
                                onError: function(result) {
                                    alert("Pembayaran Gagal!");
                                    payButton.innerHTML = "Buat Pesanan Sekarang ✨";
                                    payButton.disabled = false;
                                },
                                // ✨ INI PENYELAMATNYA: Jika ditutup langsung pindah ✨
                                onClose: function() {
                                    window.location.href = "{{ route('customer.orders.index') }}";
                                }
                            });
                        } else if (res.redirect_url) {
                            window.location.href = res.redirect_url;
                        } else {
                            alert('Error: ' + (res.error || 'Terjadi kesalahan'));
                            payButton.innerHTML = "Buat Pesanan Sekarang ✨";
                            payButton.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        payButton.disabled = false;
                    });
            });
        });
    </script>
</x-app-layout>
