<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;


class ReviewController extends Controller
{public function index()
{
    // Mengambil ulasan terbaru beserta data user dan produknya
    $reviews = Review::with(['user', 'product'])->latest()->get();
    return view('admin.reviews.index', compact('reviews'));
}

public function store(Request $request, $id)
{
    // Validasi data yang masuk
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string',
    ]);

    // Simpan data ulasan ke database
    \App\Models\Review::create([
        'user_id' => auth()->id(),
        'product_id' => $id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    // REDIRECT: Pindah ke halaman detail produk + scroll ke #review-section
    return redirect()->route('customer.products.show', $id)
                     ->with('success', 'Ulasan cantikmu berhasil dibagikan! ✨')
                     ->withFragment('review-section');
}

public function create($product_id) {
    $product = Product::findOrFail($product_id);
    return view('customer.reviews.create', compact('product'));
}
}
