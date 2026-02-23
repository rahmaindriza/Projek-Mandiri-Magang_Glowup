<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
{
    return view('admin.categories.create');
}

public function store(Request $request)
{
    $request->validate(['name' => 'required|unique:categories,name']);
    \App\Models\Category::create($request->all());
    // Setelah simpan, balikkan ke halaman index
    return redirect()->route('admin.categories.index')->with('success', 'Kategori baru berhasil ditambahkan!');
}



    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required']);
        $category->update($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui!');
    }

    public function destroy(Category $category)
    {
        // Cek apakah ada produk yang pakai kategori ini sebelum dihapus
        if($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak bisa dihapus karena masih digunakan produk!');
        }
        $category->delete();
        return redirect()->back()->with('success', 'Kategori dihapus!');
    }
}
