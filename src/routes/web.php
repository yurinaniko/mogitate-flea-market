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

    // 先に edit を書く！
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');

    // その後に update を書く！
    Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');

    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});
