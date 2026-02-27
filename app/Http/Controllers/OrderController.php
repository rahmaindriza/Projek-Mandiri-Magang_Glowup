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
    // Hanya mengambil data belanja milik user tersebut
    $orders = Order::where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->get();

    // Mengarah ke resources/views/dashboard.blade.php
    // Tanpa variabel grafik ($months/$totals) agar grafik tidak muncul
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
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            // Cek jika transaksi berhasil
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {

                $orderIdParts = explode('-', $request->order_id);
                $orderId = $orderIdParts[1];

                $order = \App\Models\Order::find($orderId);

                if ($order && $order->status !== 'success') {
                    // HANYA update status ke success
                    // Stok tidak dikurangi di sini karena sudah dikurangi saat checkout
                    $order->update(['status' => 'success']);

                    return response()->json(['message' => 'Status pembayaran berhasil diperbarui']);
                }
            }
        }

        return response()->json(['message' => 'Callback diterima'], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        // Eager loading items dan product
        $order = Order::with('items.product')->findOrFail($id);
        $oldStatus = $order->status;

        $request->validate([
            'status' => 'required|in:pending,success,dikirim,selesai,dibatalkan'
        ]);

        // Update status baru
        $order->update(['status' => $request->status]);

        // LOGIKA PENGEMBALIAN STOK ✨
        // Jika status diubah menjadi 'dibatalkan' dan status sebelumnya bukan 'dibatalkan'
        if ($request->status == 'dibatalkan' && $oldStatus != 'dibatalkan') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    // Kembalikan stok yang sebelumnya terpotong
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }
        // Jika status dikembalikan dari 'dibatalkan' ke status aktif lagi (misal dikirim/selesai)
        elseif ($oldStatus == 'dibatalkan' && in_array($request->status, ['success', 'dikirim', 'selesai'])) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    // Potong kembali stoknya
                    $item->product->decrement('stock', $item->quantity);
                }
            }
        }

        return redirect()->back()->with('success', 'Status diperbarui dan stok disesuaikan! ✨');
    }

    //laporan keuangan

    public function laporan()
    {
        // Mengambil hanya pesanan yang sudah dibayar (success/selesai)
        $orders = Order::whereIn('status', ['success', 'selesai'])
            ->with('user')
            ->latest()
            ->get();

        // Menghitung total pendapatan
        $totalPendapatan = $orders->sum('total_price');

        return view('admin.laporan.index', compact('orders', 'totalPendapatan'));
    }

   // app/Http/Controllers/OrderController.php

// UNTUK ADMIN (Grafik ADA)
public function adminDashboard()
{
    // Ambil data penjualan
    $salesData = \App\Models\Order::whereIn('status', ['success', 'selesai'])
        ->whereYear('created_at', date('Y'))
        ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

    $months = []; $totals = [];
    for ($m = 1; $m <= 12; $m++) {
        // Menggunakan nama bulan singkat (Jan, Feb, dst)
        $months[] = \Carbon\Carbon::create()->month($m)->format('M');
        // Memastikan data adalah angka (integer)
        $totals[] = (int)($salesData[$m] ?? 0);
    }

    $orders = \App\Models\Order::with('user')->latest()->take(5)->get();

    return view('admin.dashboard', compact('months', 'totals', 'orders'));
}

// UNTUK CUSTOMER (Grafik TIDAK ADA)

}
