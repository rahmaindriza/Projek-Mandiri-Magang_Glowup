<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\StockLog; // Pastikan Anda sudah membuat model StockLog
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminProductController extends Controller
{
    /**
     * Manajemen Produk (Daftar Produk)
     */
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        $lowStockProducts = Product::where('stock', '<', 5)->get();
        return view('admin.products.index', compact('products', 'lowStockProducts'));
    }

    /**
     * Dashboard Utama Admin
     */
    public function adminDashboard()
    {
        $lowStockProducts = Product::where('stock', '<', 5)->get();
        $orders = Order::with('user')->latest()->take(5)->get();
        $months = ['Jan', 'Feb', 'Mar'];
        $totals = [500000, 1200000, 183000];

        return view('admin.dashboard', compact('lowStockProducts', 'orders', 'months', 'totals'));
    }

    /**
     * MODUL LOGISTIK STOK (Sesuai Arahan Mentor)
     * Menampilkan halaman khusus pengelolaan stok dan riwayatnya
     */
    public function stockLog(Request $request)
    {
        $products = Product::orderBy('name', 'asc')->get();
        $logs = StockLog::with('product')->latest()->get();

        // Ambil ID produk dari URL jika ada
        $selectedProductId = $request->query('product_id');

        return view('admin.stock.index', compact('products', 'logs', 'selectedProductId'));
    }

    /**
     * Memproses penambahan stok secara aman dan mencatat riwayat
     */
    public function updateStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out', // 'in' untuk tambah, 'out' untuk kurang
            'qty' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ]);

        $product = Product::findOrFail($request->product_id);
        $stockBefore = $product->stock;

        if ($request->type == 'in') {
            // Logika Tambah Stok
            $product->increment('stock', $request->qty);
            $amountDisplay = "+" . $request->qty;
        } else {
            // Logika Kurang Stok
            if ($product->stock < $request->qty) {
                return back()->with('error', 'Gagal! Stok sisa tidak mencukupi untuk dikurangi.');
            }
            $product->decrement('stock', $request->qty);
            $amountDisplay = "-" . $request->qty;
        }

        // Simpan ke Riwayat Mutasi
        StockLog::create([
            'product_id' => $product->id,
            'qty_added' => ($request->type == 'in' ? $request->qty : -$request->qty),
            'stock_before' => $stockBefore,
            'stock_after' => $product->stock,
            'admin_name' => Auth::user()->name,
            'description' => $request->description ?? ($request->type == 'in' ? 'Barang Masuk' : 'Penyesuaian Barang Keluar'),
        ]);

        return redirect()->back()->with('success', "Stok {$product->name} berhasil diperbarui ({$amountDisplay})! 📦");
    }

    /**
     * CRUD Dasar Produk (Create, Store, Edit, Update, Destroy)
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'required|image|max:2048',
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk Berhasil Disimpan! ✨');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'category_id', 'price', 'stock', 'description']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui! ✨');
    }

    public function show($id)
    {
        // Mengambil data produk beserta kategorinya
        $product = Product::with('category')->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus! 🗑️');
    }
}
