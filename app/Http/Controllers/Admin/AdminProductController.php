<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;

class AdminProductController extends Controller
{
   public function index()
    {
        $products = Product::with('category')->latest()->get();
        $lowStockProducts = Product::where('stock', '<', 5)->get();
        return view('admin.products.index', compact('products', 'lowStockProducts'));
    }

    // Halaman Utama Dashboard Admin [Perbaikan Utama]
    public function adminDashboard()
{
    // 1. Ambil data stok di bawah 5 pcs
    $lowStockProducts = Product::where('stock', '<', 5)->get();

    // 2. Ambil data pesanan
    $orders = Order::with('user')->latest()->take(5)->get();

    // 3. Data grafik
    $months = ['Jan', 'Feb', 'Mar'];
    $totals = [500000, 1200000, 183000];

    // 4. Kirim variabel ke view
    return view('admin.dashboard', compact('lowStockProducts', 'orders', 'months', 'totals'));
}

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

        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui! ✨');
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

    public function addStock($id) {
        $product = Product::findOrFail($id);
        return view('admin.products.add-stock', compact('product'));
    }

    public function updateStock(Request $request, $id) {
        $request->validate(['qty' => 'required|integer|min:1']);
        $product = Product::findOrFail($id);

        $product->stock += $request->qty;
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Stok ' . $product->name . ' berhasil ditambah!');
    }


}

