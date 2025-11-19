<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Season;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $sort = $request->input('sort');

        $query = Product::with('seasons');

        if (!empty($keyword)) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($sort === 'high') {
            $query->orderBy('price', 'desc');
        }
        if ($sort === 'low') {
            $query->orderBy('price', 'asc');
        }

        $products = $query->paginate(6);
        $products->appends([
            'keyword' => $keyword,
            'sort' => $sort,
        ]);

        return view('products.index', compact('products', 'keyword', 'sort'));
    }

    public function store(ProductRequest $request)
    {
        // ①バリデーション済みデータ
        $validated = $request->validated();

       // ②画像アップロード（バリデーション通過後）
        $image = $request->file('image')->store('products', 'public');
        $validated['image'] = basename($image);

        // ③DB登録
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => basename($image),
        ]);
        // ④seasons登録
        if ($request->seasons) {
        $product->seasons()->sync($request->seasons);
        }

        return redirect()->route('products.index')->with('success', '商品を登録しました');
    }

    public function edit($id)
    {
        $product = Product::with('seasons')->findOrFail($id);
        $seasons = Season::all();
        return view('products.edit', compact('product', 'seasons'));
    }

    public function create()
    {
        // 季節一覧を画面に渡す
        $seasons = Season::all();
        return view('products.create', compact('seasons'));
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        // ★ 現在の画像名確保
        $imageName = $product->image;

        // 画像削除チェック ON の場合
        if ($request->delete_image === "1") {
        Storage::disk('public')->delete('products/' . $imageName);
        $imageName = null;
        }
        // ★ 新しい画像がアップロードされた場合のみ変更
        if ($request->hasFile('image')) {
        $image = $request->file('image')->store('products', 'public');
        $imageName = basename($image);
        }

        // バリデーション済みデータ取得
        $validated = $request->validated();

        // validated に必ず image をセット
        $validated['image'] = $imageName;

       // ★ データ更新
        $product->update($validated);

       // seasons 更新
        $product->seasons()->sync($request->input('seasons', []));

        return redirect()
        ->route('products.index')
        ->with('success', '商品情報を更新しました');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        // 画像ファイルも削除
        Storage::disk('public')->delete('products/' . $product->image);
        // データ削除
        $product->delete();
        return redirect()
            ->route('products.index')
            ->with('delete', '商品を削除しました');
    }
}