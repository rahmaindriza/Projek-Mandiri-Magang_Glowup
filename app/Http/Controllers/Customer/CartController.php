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
    // Cek apakah produk sudah ada di keranjang user
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

    // Jika user klik "Beli Sekarang" (langsung checkout)
    if ($request->redirect_to == 'checkout') {
        $newCartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();
        return redirect()->route('checkout.index', ['selected_items' => [$newCartItem->id]]);
    }

    return redirect()->back()->with('success', 'Produk berhasil masuk keranjang! ✨');
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

    // URL Midtrans otomatis berdasarkan mode
    $url = $isProduction
        ? 'https://app.midtrans.com/snap/v1/transactions'
        : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

    try {
        // Ambil user menggunakan Auth facade
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
        }

        // Ambil data keranjang berdasarkan item yang dipilih
        $cartItems = Cart::with('product')->whereIn('id', $request->item_ids)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Keranjang kosong.'], 400);
        }

        $total = (int) $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        // Simpan Data Pesanan ke tabel orders
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $total,
            'status' => 'pending',
            'phone' => $request->phone, // Ambil dari input form
            'address' => $request->address,
        ]);

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

        // Eksekusi cURL (Gunakan kode cURL yang sudah kita bahas sebelumnya agar tidak error SSL)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($serverKey . ':')
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bypass SSL Laragon

        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);

        if (isset($response['token'])) {
            $snapToken = $response['token'];
            $order->update(['snap_token' => $snapToken]);

            // Hapus item dari keranjang setelah berhasil dapat token
            Cart::whereIn('id', $request->item_ids)->delete();

            return response()->json(['snap_token' => $snapToken]);
        } else {
            return response()->json(['error' => $response['error_messages'][0] ?? 'Gagal koneksi ke Midtrans'], 500);
        }

    } catch (\Exception $e) {
        return response()->json(['error' => 'Server Error: ' . $e->getMessage()], 500);
    }
}
}
