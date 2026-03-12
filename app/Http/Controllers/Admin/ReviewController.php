<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\User;


class ReviewController extends Controller
{public function index()
{
    // Mengambil ulasan terbaru beserta data user dan produknya
    $reviews = Review::with(['user', 'product'])->latest()->get();
    return view('admin.reviews.index', compact('reviews'));
}

public function store(Request $request)
{
    // 1. Validasi data
    $request->validate([
        'product_id' => 'required', // Pastikan ID produk terkirim dari form
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string',
    ]);

    // 2. Simpan data ulasan
    \App\Models\Review::create([
        'user_id' => auth()->id(),
        'product_id' => $request->product_id, // Ambil dari input form
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    // 3. REDIRECT: Pindah ke halaman detail produk tersebut ✨
    // Pastikan namanya 'product.show' sesuai dengan nama rute detail produk kamu ✨
return redirect()->route('product.show', $request->product_id)
                 ->with('success', 'Ulasan cantikmu berhasil dibagikan! ✨')
                 ->withFragment('review-section');
}

public function create($product_id) {
    $product = Product::findOrFail($product_id);
    return view('customer.reviews.create', compact('product'));
}
}
