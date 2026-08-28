<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return redirect('/products');
});

Route::prefix('products')->group(function () {

    Route::get('/', [ProductController::class, 'index'])->name('products.index');

    Route::get('/create', [ProductController::class, 'create'])->name('products.create');

    // 書き込み系は画像アップロードを伴うため、連打によるストレージ枯渇・DB肥大を
    // 防ぐレート制限を付ける（このアプリは認証が無いぶん、唯一の歯止め）。
    Route::post('/', [ProductController::class, 'store'])
        ->middleware('throttle:20,1')->name('products.store');

    // 先に edit を書く！
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');

    // その後に update を書く！
    Route::put('/{id}', [ProductController::class, 'update'])
        ->middleware('throttle:20,1')->name('products.update');

    Route::delete('/{id}', [ProductController::class, 'destroy'])
        ->middleware('throttle:20,1')->name('products.destroy');
});
