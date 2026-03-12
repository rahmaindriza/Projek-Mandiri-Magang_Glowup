<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\OrderItem;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        return view('customer.cart', compact('cartItems', 'total'));
    }

    public function store(Request $request, $productId)
    {
        // Jika hanya "+ Tambah Ke Keranjang"
        if ($request->redirect_to == 'index') {
            $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();
            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => 1
                ]);
            }
            return redirect()->back()->with('success', 'Produk berhasil masuk keranjang! ✨');
        }

        // Jika "Beli Sekarang" ✨
        // Kita TIDAK simpan ke database Cart. Kita lempar datanya langsung.
        return redirect()->route('checkout.direct', ['product_id' => $productId, 'qty' => 1]);
    }

    // Tambahkan fungsi baru ini untuk menangani Beli Sekarang Tanpa Keranjang
    public function directCheckout(Request $request, $productId)
    {
        $product = \App\Models\Product::findOrFail($productId);
        $qty = $request->query('qty', 1);

        // Kita buat objek palsu agar View Checkout tidak error (mirip struktur model Cart)
        $directItem = (object) [
            'id' => 'direct', // Tanda bahwa ini bukan dari DB
            'product_id' => $product->id,
            'product' => $product,
            'quantity' => $qty
        ];

        $cartItems = collect([$directItem]);
        $total = $product->price * $qty;

        return view('customer.checkout', compact('cartItems', 'total'));
    }

    public function update(Request $request, $id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        if ($request->action == 'increase') {
            $cartItem->increment('quantity');
        } elseif ($request->action == 'decrease' && $cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        Cart::where('user_id', Auth::id())->findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Produk dihapus.');
    }

    public function checkout(Request $request)
    {
        // Mengambil ID dari input 'selected_items'
        $selectedIds = $request->input('selected_items');

        if (!$selectedIds) {
            return redirect()->route('cart.index')->with('error', 'Pilih produk di keranjang terlebih dahulu! ✨');
        }

        // Pastikan kita mengambil data berdasarkan ID Keranjang
        $cartItems = Cart::with('product')->whereIn('id', $selectedIds)->where('user_id', Auth::id())->get();

        // Jika ternyata kosong (karena ID salah), kembalikan ke keranjang
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Data tidak ditemukan, coba lagi ya.');
        }

        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return view('customer.checkout', compact('cartItems', 'total'));


    }


    public function processCheckout(Request $request)
{
    $serverKey = config('midtrans.server_key');
    $isProduction = config('midtrans.is_production');

    $url = $isProduction
        ? 'https://app.midtrans.com/snap/v1/transactions'
        : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
        }

        // --- LOGIKA DETEKSI SUMBER DATA (KERANJANG VS DIRECT) ✨ ---
        // Jika ID pertama adalah 'direct', berarti Beli Sekarang
        if ($request->item_ids[0] == 'direct') {
            $product = \App\Models\Product::findOrFail($request->product_id);
            $cartItems = collect([(object)[
                'product' => $product,
                'product_id' => $product->id,
                'quantity' => $request->qty, // Ambil qty terbaru dari JS
            ]]);
        } else {
            // Jika bukan 'direct', berarti dari Keranjang
            $cartItems = Cart::with('product')->whereIn('id', $request->item_ids)->get();
        }

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Data pesanan tidak ditemukan.'], 400);
        }

        // --- VALIDASI STOK ---
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return response()->json([
                    'error' => "Maaf, stok {$item->product->name} tidak mencukupi!"
                ], 400);
            }
        }

        $total = (int) $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        // 1. Simpan Data Pesanan Utama
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $total,
            'phone' => $request->phone,
            'province' => $request->province,
            'city' => $request->city,
            'district' => $request->district,
            'village' => $request->village,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'payment_method' => $request->payment_method,
            'status' => ($request->payment_method == 'cod') ? 'dikemas' : 'belum bayar',
        ]);

        // 2. Simpan Detail Item & Kurangi Stok
        foreach ($cartItems as $item) {
            \App\Models\OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);

            // Kurangi stok produk asli di database
            $productOriginal = \App\Models\Product::find($item->product_id);
            $productOriginal->decrement('stock', $item->quantity);
        }

        // Hapus item dari keranjang HANYA JIKA pesanan berasal dari keranjang
        if ($request->item_ids[0] != 'direct') {
            Cart::whereIn('id', $request->item_ids)->delete();
        }

        // 3. PROSES PEMBAYARAN
        if ($request->payment_method == 'transfer') {
            $params = [
                'transaction_details' => [
                    'order_id' => 'GLOW-' . $order->id . '-' . time(),
                    'gross_amount' => $total,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $request->phone,
                ],
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($serverKey . ':')
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($result, true);

            if (isset($response['token'])) {
                $snapToken = $response['token'];
                $order->update(['snap_token' => $snapToken]);
                return response()->json(['snap_token' => $snapToken]);
            } else {
                return response()->json(['error' => 'Gagal koneksi ke Midtrans'], 500);
            }
        } else {
            return response()->json([
                'redirect_url' => route('customer.orders.index'),
                'message' => 'Pesanan COD berhasil dibuat!'
            ]);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'Server Error: ' . $e->getMessage()], 500);
    }
}
}
