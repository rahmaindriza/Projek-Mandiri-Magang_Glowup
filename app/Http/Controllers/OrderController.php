<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar riwayat pesanan untuk Customer
     */
    public function index()
{
    // Mengambil data pesanan milik user yang sedang login
    $orders = Order::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

    // PERBAIKAN: Harus ke 'dashboard' karena itu file yang kamu edit
    return view('dashboard', compact('orders'));
}

public function customerIndex()
{
    $orders = Order::where('user_id', Auth::id())->latest()->get();
    return view('customer.orders.index', compact('orders'));
}

    /**
     * Menampilkan daftar pesanan masuk untuk Admin
     */
    public function adminIndex()
    {
        // Ambil semua order beserta data usernya untuk ditampilkan di dashboard admin
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan tertentu
     */
    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        // UBAH: arahkan ke folder customer
        return view('customer.orders.show', compact('order'));
    }

    /**
     * Callback dari Midtrans untuk update status otomatis
     */
    public function midtransCallback(Request $request)
{
    $serverKey = config('midtrans.server_key');
    // Verifikasi tanda tangan keamanan dari Midtrans
    $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

    if ($hashed == $request->signature_key) {
        // Cek jika transaksi berhasil (settlement atau capture)
        if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {

            // Mengambil ID pesanan dari format GLOW-{ID}-{TIME}
            $orderIdParts = explode('-', $request->order_id);
            $orderId = $orderIdParts[1];

            $order = \App\Models\Order::find($orderId);

            if ($order) {
                // OTOMATIS UBAH STATUS JADI SUCCESS
                $order->update(['status' => 'success']);
            }
        }
    }

    return response()->json(['status' => 'ok']);
}

public function updateStatus(Request $request, $id)
{
    $order = Order::findOrFail($id);

    // Validasi input status
    $request->validate([
        'status' => 'required|in:pending,success,dikirim,selesai,dibatalkan'
    ]);

    $order->update([
        'status' => $request->status
    ]);

    return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui! ✨');
}
}
