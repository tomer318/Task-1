<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use Illuminate\Support\Facades\Route;

// Trang chủ Store E-commerce
Route::get('/', function () {
    $categories = Category::withCount('products')->get();
    $featuredProducts = Product::with('category')->latest()->take(12)->get();
    return view('welcome', compact('categories', 'featuredProducts'));
})->name('home');

// Dashboard chuyển hướng theo quyền
Route::get('/dashboard', function (Request $request) {
    $user = $request->user();

    if ($user && $user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Quản lý tài khoản cá nhân
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Nhóm quản trị dành riêng cho Role: Admin
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();
        $recentProducts = Product::with('category')->latest()->take(6)->get();

        return view('admin.dashboard', compact('totalProducts', 'totalCategories', 'totalUsers', 'recentProducts'));
    })->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('brands', BrandController::class);
});

require __DIR__.'/auth.php';