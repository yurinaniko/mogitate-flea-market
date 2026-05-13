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
        $validated = $request->validated();
        $image = $request->file('image')->store('products', 'public');
        $validated['image'] = basename($image);

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => basename($image),
        ]);
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
        $seasons = Season::all();
        return view('products.create', compact('seasons'));
    }
    public function update(ProductRequest $request, $id)
    {
    $product = Product::findOrFail($id);
    $validated = $request->validated();

    $oldImage = $product->image;

    $product->seasons()->sync($validated['seasons']);

    if ($request->delete_image === "1") {
        if ($oldImage) {
            Storage::disk('public')->delete('products/' . $oldImage);
        }
        $validated['image'] = null;
        }

    if ($request->hasFile('image')) {

        if ($oldImage) {
            Storage::disk('public')->delete('products/' . $oldImage);
        }

        $path = $request->file('image')->store('products', 'public');
        $validated['image'] = basename($path);
        }

    $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', '商品情報を更新しました');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        Storage::disk('public')->delete('products/' . $product->image);
        $product->delete();
        return redirect()
            ->route('products.index')
            ->with('delete', '商品を削除しました');
    }
}