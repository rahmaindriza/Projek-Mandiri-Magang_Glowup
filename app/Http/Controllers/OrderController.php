<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar riwayat pesanan untuk Customer (Dashboard)
     */
    public function index()
    {
        $orders = Order::with('orderItems.product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('orders'));
    }

    /**
     * Menampilkan daftar riwayat pesanan (Halaman Khusus Riwayat)
     */
    public function customerIndex()
    {
        $orders = Order::with('orderItems.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Customer klik "Pesanan Diterima" ✨
     */
    public function markAsSelesai($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status == 'dikirim') {
            $order->update(['status' => 'selesai']);
            return redirect()->back()->with('success', 'Pesanan selesai! Silakan berikan ulasan terbaikmu. ✨');
        }

        return redirect()->back()->with('error', 'Pesanan belum bisa diselesaikan.');
    }

    /**
     * Customer Membatalkan Pesanan ❌
     */
    public function cancelOrder($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status == 'dikemas') {
            $order->update(['status' => 'dibatalkan']);

            foreach ($order->orderItems as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return redirect()->back()->with('error', 'Pesanan tidak bisa dibatalkan.');
    }

    /**
     * Tampilan Riwayat Pesanan Admin
     */
    public function adminIndex()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Detail Pesanan sisi Customer (Menampilkan Alamat Lengkap & Resi)
     */
    public function show($id)
    {
        $order = Order::with('orderItems.product')->where('user_id', Auth::id())->findOrFail($id);
        return view('customer.orders.show', compact('order'));
    }

    /**
     * Detail Pesanan sisi Admin
     */
    public function adminShow($id)
    {
        $order = Order::with(['user', 'orderItems.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update Status & Input Resi oleh Admin 🚚
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;

        $request->validate([
            'status' => 'required|in:belum bayar,dikemas,dikirim,selesai,dibatalkan',
            'tracking_number' => 'nullable|string' // Validasi untuk nomor resi
        ]);

        $updateData = ['status' => $request->status];

        // Jika status diubah ke dikirim, simpan nomor resi
        if ($request->status == 'dikirim' && $request->tracking_number) {
            $updateData['tracking_number'] = $request->tracking_number;
        }

        $order->update($updateData);

        // Pengembalian stok jika dibatalkan
        if ($request->status == 'dibatalkan' && $oldStatus != 'dibatalkan') {
            foreach ($order->orderItems as $item) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        return redirect()->back()->with('success', 'Status dan informasi pengiriman berhasil diperbarui!');
    }

    /**
     * Callback Midtrans ✨
     */
   public function midtransCallback(Request $request)
{
    $serverKey = config('midtrans.server_key');
    $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

    if ($hashed == $request->signature_key) {
        $order = Order::find($request->order_id);
        if ($order) {
            $status = $request->transaction_status;

            // Jika pembayaran berhasil (Settlement / Capture)
            if ($status == 'capture' || $status == 'settlement') {
                $order->update(['status' => 'dikemas']);
            }
            // Jika expired atau dibatalkan, tetap 'belum bayar' atau 'dibatalkan'
            elseif (in_array($status, ['expire', 'cancel', 'deny'])) {
                $order->update(['status' => 'belum bayar']);
            }
        }
    }
    return response()->json(['status' => 'ok']);
}

    /**
     * Laporan Keuangan
     */
    public function laporan()
    {
        $orders = Order::where('status', 'selesai')->with('user')->latest()->get();
        $totalPendapatan = $orders->sum('total_price');
        return view('admin.laporan.index', compact('orders', 'totalPendapatan'));
    }

    /**
     * Grafik Dashboard Admin
     */
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



    // app/Http/Controllers/OrderController.php

    public function updateStatusManual($id)
    {
        // Cari pesanan berdasarkan ID
        $order = \App\Models\Order::find($id);

        // Cek apakah pesanan ada dan statusnya masih 'belum bayar'
        if ($order && $order->status == 'belum bayar') {
            $order->update(['status' => 'dikemas']); // Ubah status ke dikemas ✨
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal update status'], 404);
    }
}
