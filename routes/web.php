<?php

use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Customer\CartController;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
use Illuminate\Http\Request;





/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- HALAMAN PUBLIK ---
Route::get('/', function () {
    $products = Product::with('category')->latest()->get();
    return view('welcome', compact('products'));
});

Route::get('/product/{id}', function ($id) {
    $product = Product::with('category')->findOrFail($id);

    // PERBAIKAN: Gunakan string 'product', bukan variabel $product
    return view('product.detail', compact('product'));
})->name('product.show');

// --- DASHBOARD UMUM ---
Route::get('/dashboard', [OrderController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// --- GRUP ROUTE KHUSUS ADMIN ---
// --- GRUP ROUTE KHUSUS ADMIN ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Rute Dashboard Admin
    Route::get('/dashboard', [OrderController::class, 'adminDashboard'])->name('dashboard');

    // Rute Pesanan Masuk (Index)
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');

    // FIX ERROR PESANAN MASUK ✨
    // Pastikan nama rutenya 'orders.update' agar dipanggil sebagai 'admin.orders.update'
    Route::patch('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update');

    // Rute Detail Pesanan Admin
    Route::get('/orders/{id}/detail', [OrderController::class, 'adminShow'])->name('orders.show');



    // Manajemen Produk (Otomatis mencakup index, create, store, edit, update, destroy)
    Route::resource('products', AdminProductController::class);

    // Manajemen Kategori
    Route::resource('categories', CategoryController::class);

    // Manajemen Pesanan oleh Admin
    // Manajemen Pesanan Admin
Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
Route::get('/orders/{id}/detail', [OrderController::class, 'adminShow'])->name('orders.show');

// INI YANG PALING PENTING: Nama harus 'orders.update' ✨
Route::patch('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update');
});

// --- GRUP ROUTE KHUSUS CUSTOMER (SUDAH LOGIN) ---
Route::middleware('auth')->group(function () {

    // Profile (Kode Asli Kamu)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Katalog & Keranjang (Kode Asli Kamu)
    Route::get('/customer/katalog', function () {
        $products = Product::with('category')->latest()->get();
        return view('customer.katalog', compact('products'));
    })->name('customer.katalog');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout & Midtrans (Kode Asli Kamu)
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/proses', [CartController::class, 'processCheckout'])->name('checkout.process');

    // Riwayat Pesanan Customer (DIPERBAIKI DISINI ✨)
    Route::get('/riwayat-pesanan', [OrderController::class, 'customerIndex'])->name('orders.index');
    Route::get('/riwayat-pesanan/{id}', [OrderController::class, 'show'])->name('orders.show');

    // TAMBAHKAN 2 BARIS INI agar tombol BATAL dan SELESAI tidak error 404
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/orders/{id}/selesai', [OrderController::class, 'markAsSelesai'])->name('orders.selesai');
});
// --- CALLBACK MIDTRANS (Luar Middleware Auth agar Midtrans bisa akses) ---
Route::post('/midtrans/callback', [OrderController::class, 'midtransCallback']);

//pencarian product oleh customer
Route::get('/customer/katalog', [App\Http\Controllers\ProductController::class, 'katalog'])
    ->name('customer.katalog')
    ->middleware(['auth']);


//laporan keuangan admin
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // ... rute lainnya ...
    Route::get('/laporan', [App\Http\Controllers\OrderController::class, 'laporan'])->name('laporan.index');
});

// Cari baris ini di routes/web.php
Route::get('/admin/dashboard', [OrderController::class, 'adminDashboard'])->name('admin.dashboard');

// Kelola user oleh admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Rute Manajemen User Lengkap
    Route::get('/users', [UserController::class, 'index'])->name('users.index');          // Daftar User
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create'); // Form Tambah User baru
    Route::post('/users', [UserController::class, 'store'])->name('users.store');        // Simpan User baru
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');  // Form Edit User
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');  // Proses Update User
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy'); // Hapus User

});

//peringatan stok menipis produk oleh admin
Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\AdminProductController::class, 'adminDashboard'])->name('admin.dashboard');

// MODUL LOGISTIK STOK (Terpusat)
Route::get('/admin/stock', [AdminProductController::class, 'stockLog'])->name('admin.stock.index');
Route::post('/admin/stock', [AdminProductController::class, 'updateStock'])->name('admin.stock.store');

// Detail produk untuk admin
Route::get('/admin/products/{id}/detail', [AdminProductController::class, 'show'])->name('admin.products.show');

//detail pesanan untuk admin
Route::get('/admin/orders/{id}/detail', [OrderController::class, 'adminShow'])->name('admin.orders.show');

Route::post('/product/{id}/review', [ReviewController::class, 'store'])->name('reviews.store');
// Pastikan strukturnya seperti ini


Route::get('/katalog/{id}', [ProductController::class, 'show'])->name('customer.products.show');
Route::post('/product/{id}/review', [ReviewController::class, 'store'])->name('reviews.store');
// Tambahkan rute untuk menampilkan halaman form ulasan
Route::get('/ulasan/tambah/{product_id}', [ReviewController::class, 'create'])->name('customer.reviews.create');

Route::get('/', function (Illuminate\Http\Request $request) {
    $categories = \App\Models\Category::all();

    // 1. Ambil 5 Ulasan Terbaik (Rating 5) ✨
    $reviews = \App\Models\Review::with('user')
        ->where('rating', 5)
        ->latest()
        ->take(6)
        ->get();

    // 2. Logika Search & Filter Produk
    $products = \App\Models\Product::with('category')
        ->when($request->search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
        ->when($request->category, function ($query, $categoryId) {
            return $query->where('category_id', $categoryId);
        })
        ->get();

    return view('welcome', compact('products', 'categories', 'reviews'));
})->name('welcome');

require __DIR__ . '/auth.php';
