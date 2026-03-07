<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar riwayat pesanan untuk Customer
     */
    public function index()
    {
        $orders = Order::with('orderItems.product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('orders'));
    }

    public function customerIndex()
    {
        $orders = Order::with('orderItems.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * FUNGSI BARU: Customer klik "Pesanan Diterima" ✨
     */
    public function markAsSelesai($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        // Hanya bisa diselesaikan jika statusnya 'dikirim'
        if ($order->status == 'dikirim') {
            $order->update(['status' => 'selesai']);
            return redirect()->back()->with('success', 'Pesanan selesai! Silakan berikan ulasan terbaikmu. ✨');
        }

        return redirect()->back()->with('error', 'Pesanan belum bisa diselesaikan.');
    }

    /**
     * FUNGSI BARU: Customer Membatalkan Pesanan ❌
     */
    public function cancelOrder($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        // Hanya bisa dibatalkan jika belum bayar
        if ($order->status == 'belum bayar') {
            $order->update(['status' => 'dibatalkan']);

            // Kembalikan stok produk
            foreach ($order->orderItems as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return redirect()->back()->with('error', 'Pesanan tidak bisa dibatalkan.');
    }

    public function adminIndex()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('orderItems.product')->where('user_id', Auth::id())->findOrFail($id);
        return view('customer.orders.show', compact('order'));
    }

    public function adminShow($id)
    {
        $order = Order::with(['user', 'orderItems.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Callback Midtrans: Update status ke 'dikemas' ✨
     */
   public function midtransCallback(Request $request)
{
    $serverKey = config('midtrans.server_key');
    $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

    if ($hashed == $request->signature_key) {
        $orderId = $request->order_id;

        // Ambil ID murni jika pakai format INV-10
        if (str_contains($orderId, '-')) {
            $orderIdParts = explode('-', $orderId);
            $orderId = end($orderIdParts);
        }

        $order = Order::find($orderId);

        if ($order) {
            // 1. JIKA BERHASIL BAYAR ✨
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                // Status langsung pindah ke 'dikemas'
                $order->update(['status' => 'dikemas']);

                return response()->json(['message' => 'Status berubah: Dikemas']);
            }

            // 2. JIKA GAGAL/CANCEL/EXPIRE
            elseif (in_array($request->transaction_status, ['expire', 'cancel', 'deny'])) {
                $order->update(['status' => 'dibatalkan']);

                // Kembalikan stok (Gunakan relasi yang benar, asumsikan 'items' atau 'orderItems')
                foreach ($order->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                }

                return response()->json(['message' => 'Status berubah: Dibatalkan']);
            }
        }
    }
    return response()->json(['message' => 'Callback diproses'], 200);
}

    /**
     * Update Status oleh Admin
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        $oldStatus = $order->status;

        $request->validate([
            'status' => 'required|in:belum bayar,dikemas,dikirim,selesai,dibatalkan'
        ]);

        $order->update(['status' => $request->status]);

        // Pengembalian stok jika dibatalkan
        if ($request->status == 'dibatalkan' && $oldStatus != 'dibatalkan') {
            foreach ($order->orderItems as $item) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        return redirect()->back()->with('success', 'Status berhasil diperbarui!');
    }

    public function laporan()
    {
        $orders = Order::where('status', 'selesai')->with('user')->latest()->get();
        $totalPendapatan = $orders->sum('total_price');
        return view('admin.laporan.index', compact('orders', 'totalPendapatan'));
    }

    public function adminDashboard()
    {
        $salesData = Order::where('status', 'selesai')
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $months = [];
        $totals = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = \Carbon\Carbon::create()->month($m)->format('M');
            $totals[] = (int)($salesData[$m] ?? 0);
        }

        $orders = Order::with('user')->latest()->take(5)->get();
        return view('admin.dashboard', compact('months', 'totals', 'orders'));
    }
}
