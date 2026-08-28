<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * シーディングの再現性。
 *
 * シード画像がGit管理外で「クローン直後は商品画像が全部404」だった
 * 問題の再発防止。シーダーが database/seeders/images から storage へ
 * 画像をコピーし、全商品の画像ファイルが実在することを固定する。
 */
class ProductSeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function シーディングで商品10件と全画像ファイルが揃う()
    {
        Storage::fake('public');

        $this->seed();

        $this->assertSame(10, Product::count());
        foreach (Product::pluck('image') as $image) {
            Storage::disk('public')->assertExists('products/' . $image);
        }
    }
}
