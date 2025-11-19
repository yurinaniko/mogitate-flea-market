<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return redirect('/products');
});

Route::prefix('products')->group(function () {

    Route::get('/', [ProductController::class, 'index'])->name('products.index');

    Route::get('/create', [ProductController::class, 'create'])->name('products.create');

    Route::post('/', [ProductController::class, 'store'])->name('products.store');

    // ★ 商品更新（edit より上）
    Route::put('/{id}/update', [ProductController::class, 'update'])->name('products.update');

    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // ★ 商品詳細（兼 編集画面）
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
});
