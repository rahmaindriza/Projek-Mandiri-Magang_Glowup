<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
   public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'category_id' => 'required|exists:categories,id', // Tambahkan ini
        'price' => 'required|numeric',
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $imagePath = $request->file('image')->store('products', 'public');

    \App\Models\Product::create([
        'category_id' => $request->category_id, // Pastikan ini ada
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'stock' => $request->stock,
        'image' => $imagePath,
    ]);

    return redirect()->back()->with('success', 'Produk skincare berhasil disimpan!');
}
}
