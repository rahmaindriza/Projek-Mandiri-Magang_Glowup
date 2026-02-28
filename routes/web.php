<?php

use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Customer\CartController;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminProuctController;


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
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Halaman Utama Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');


    // Manajemen Produk (Otomatis mencakup index, create, store, edit, update, destroy)
    Route::resource('products', AdminProductController::class);

    // Manajemen Kategori
    Route::resource('categories', CategoryController::class);

    // Manajemen Pesanan oleh Admin
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::patch('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// --- GRUP ROUTE KHUSUS CUSTOMER (SUDAH LOGIN) ---
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Katalog & Keranjang
    Route::get('/customer/katalog', function () {
        $products = Product::with('category')->latest()->get();
        return view('customer.katalog', compact('products'));
    })->name('customer.katalog');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout & Midtrans
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/proses', [CartController::class, 'processCheckout'])->name('checkout.process');

    // Riwayat Pesanan Customer
    Route::get('/riwayat-pesanan', [OrderController::class, 'customerIndex'])->name('orders.index');
    Route::get('/riwayat-pesanan/{id}', [OrderController::class, 'show'])->name('orders.show');
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
//Update stok produk oleh admin
Route::get('/admin/products/{id}/add-stock', [AdminProductController::class, 'addStock'])->name('admin.products.addStock');
Route::post('/admin/products/{id}/update-stock', [AdminProductController::class, 'updateStock'])->name('admin.products.updateStock');

//peringatan stok menipis produk oleh admin
Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\AdminProductController::class, 'adminDashboard'])->name('admin.dashboard');

require __DIR__.'/auth.php';
