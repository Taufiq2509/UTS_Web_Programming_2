<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes - UTS Web Programming 2 (Inventory & Sales Management System)
|--------------------------------------------------------------------------
*/

// Halaman Utama -> Redirect cerdas sesuai status auth dan role
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'owner') return redirect()->route('owner.dashboard');
        if ($role === 'staf') return redirect()->route('staf.dashboard');
        return redirect()->route('customer.dashboard');
    }
    return redirect()->route('login');
});

// Rute Autentikasi Tamu (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [AuthenticatedSessionController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthenticatedSessionController::class, 'register']);
});

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Universal Dashboard Redirector
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'owner') return redirect()->route('owner.dashboard');
    if ($role === 'staf') return redirect()->route('staf.dashboard');
    return redirect()->route('customer.dashboard');
})->middleware('auth')->name('dashboard');

// =========================================================================
// 1. ROUTE KHUSUS OWNER (Admin Akses Penuh: Master Data, User, Laporan, Batal Transaksi)
// =========================================================================
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'owner'])->name('dashboard');
    
    // Master Data
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    
    // Transaksi Pembelian & Pembatalan Pemulihan Stok
    Route::resource('purchases', PurchaseController::class);
    Route::post('/purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');
    
    // Transaksi Penjualan & Pembatalan Pemulihan Stok
    Route::resource('sales', SaleController::class);
    Route::post('/sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');
    
    // Kelola Pengguna & Role
    Route::resource('users', UserController::class);
});

// =========================================================================
// 2. ROUTE KHUSUS STAF (Operasional Stok & Transaksi)
// =========================================================================
Route::middleware(['auth', 'role:staf'])->prefix('staf')->name('staf.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'staf'])->name('dashboard');
    
    // Staf dapat melihat katalog produk dan detailnya
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    
    // Transaksi Pembelian (Stock-in)
    Route::resource('purchases', PurchaseController::class)->except(['edit', 'update', 'destroy']);
    
    // Transaksi Penjualan (Stock-out)
    Route::resource('sales', SaleController::class)->except(['edit', 'update', 'destroy']);
});

// =========================================================================
// 3. ROUTE KHUSUS PEMBELI (Customer: Katalog Produk & Riwayat Belanja)
// =========================================================================
Route::middleware(['auth', 'role:pembeli'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'customer'])->name('dashboard');
    Route::post('/order', [CustomerOrderController::class, 'placeOrder'])->name('order');
});

// Fallback Helper Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/products', function () {
        return auth()->user()->role === 'owner' 
            ? redirect()->route('owner.products.index') 
            : redirect()->route('staf.products.index');
    })->name('products.index');
    
    Route::get('/categories', function () {
        return redirect()->route('owner.categories.index');
    })->name('categories.index');
    
    Route::get('/suppliers', function () {
        return redirect()->route('owner.suppliers.index');
    })->name('suppliers.index');
});
