<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return view('admin.products.index', compact('products'));
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
    // app/Http/Controllers/Admin/AdminProductController.php

public function edit($id)
{
    $product = \App\Models\Product::findOrFail($id);
    $categories = \App\Models\Category::all(); // Pastikan kategori dikirim ke view
    return view('admin.products.edit', compact('product', 'categories'));
}

public function update(Request $request, $id)
{
    $product = \App\Models\Product::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'category_id' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'image' => 'nullable|image|max:2048', // Nullable karena foto tidak wajib ganti
    ]);

    $data = [
        'name' => $request->name,
        'category_id' => $request->category_id,
        'price' => $request->price,
        'stock' => $request->stock,
        'description' => $request->description,
    ];

    // Jika ada upload foto baru
    if ($request->hasFile('image')) {
        // Hapus foto lama agar tidak memenuhi memori
        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($data);

    return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui! ✨');
}

public function destroy($id)
{
    $product = Product::findOrFail($id);

    // Hapus file gambar dari storage agar tidak memenuhi memori
    if ($product->image) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
    }

    // Hapus data dari database
    $product->delete();

    return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus! 🗑️');
}
}
