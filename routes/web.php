<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\DebugController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//  VULN #10: Debug endpoint terbuka tanpa auth apapun
Route::get('/api/debug', [DebugController::class, 'index']);
Route::get('/api/debug/orders', [DebugController::class, 'orders']);

//  VULN #1 & #2: SQL Injection + XSS via ?search=
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

//  VULN #1: SQL Injection via ID di URL
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// VULN #4: Broken Access Control — hanya cek auth, tidak cek role admin
Route::middleware('auth')->group(function () {
    Route::get('/admin/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/admin/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/admin/products/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/admin/products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
    //  VULN #9: CSRF — route DELETE tanpa CSRF verification (via VerifyCsrfToken exception)
    Route::delete('/admin/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    //  VULN #8: quantity diterima dari POST body tanpa validasi
    Route::post('/cart/{product_id}', [CartController::class, 'add'])->name('cart.add');

    //  VULN #3 & #9: IDOR + tidak ada CSRF check (cart_id bisa milik user lain)
    Route::post('/cart/remove/{cart_id}', [CartController::class, 'remove'])->name('cart.remove')
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    //  VULN #7: total_price diterima dari POST body
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');

    //  VULN #3: IDOR — /order/{id} bisa akses order user lain
    Route::get('/order/{order_id}', [OrderController::class, 'show'])->name('order.show');

    //  VULN #3: Semua order semua user tampil
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

require __DIR__.'/auth.php';
