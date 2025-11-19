<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'キウイ', 'price' => 800, 'description' => 'キウイは甘みと酸味のバランスが絶妙なフルーツです。ビタミンCなどの栄養素も豊富で、美容効果や疲労回復効果も期待できます。もぎたてフルーツのスムージーをお召し上がりください!', 'image' => 'kiwi.png'],
            ['name' => 'ストロベリー', 'price' => 1200, 'description' => '大人から子供まで大人気のストロベリー。当店では鮮度抜群の完熟いちごを使用しています。ビタミンCはもちろん食物繊維も豊富なため、腸内環境の改善も期待できます。もぎたてフルーツのスムージーをお召し上がりください!', 'image' => 'strawberry.png'],
            ['name' => 'オレンジ', 'price' => 850, 'description' => '当店では酸味と甘みのバランスが抜群のネーブルオレンジを使用しています。爽快感は控えめで、甘さと温厚な果汁が魅力の商品です。もぎたてフルーツのスムージーをお召し上がりください！', 'image' => 'orange.png'],
            ['name' => 'スイカ', 'price' => 700, 'description' => '甘くてジューシーな食感が魅力のスイカ。全体の90%が水分のため、暑い日の水分補給や熱中症予防、カロリーが気になる方にもおすすめの商品です。', 'image' => 'watermelon.png'],
            ['name' => 'ピーチ', 'price' => 1000, 'description' => '豊潤な香りととろけるような甘さが魅力のピーチ。美味しさはもちろん見た目の可愛さも抜群の商品です。ビタミンEが豊富なため、生活習慣病の予防にもおすすめです。', 'image' => 'peach.png'],
            ['name' => 'シャインマスカット', 'price' => 1400, 'description' => '爽やかな香りと上品な甘みが特徴的なシャインマスカットは大人から子どもまで大人気のフルーツです。', 'image' => 'muscat.png'],
            ['name' => 'パイナップル', 'price' => 800, 'description' => '甘酸っぱさとトロピカルな香りが特徴のパイナップル。当店では甘さと酸味のバランスが絶妙な国産パイナップルを使用しています。', 'image' => 'pineapple.png'],
            ['name' => 'ブドウ', 'price' => 1100, 'description' => 'ブドウの中でも人気の高い国産の「巨峰」を使用しています。', 'image' => 'grapes.png'],
            ['name' => 'バナナ', 'price' => 600, 'description' => '低カロリーでありながら栄養満点のため、ダイエット中の方にもおすすめの商品です。', 'image' => 'banana.png'],
            ['name' => 'メロン', 'price' => 900, 'description' => '香りがよくジューシーで品のある甘さが人気のメロン。', 'image' => 'melon.png'],
        ];
        // 商品ごとの季節を追加
        $seasonMap = [
            0 => [3, 4],     // キウイ → 秋・冬
            1 => [1],        // ストロベリー → 春
            2 => [4],        // オレンジ → 冬
            3 => [2],        // スイカ → 夏
            4 => [1],        // ピーチ → 夏
            5 => [2, 3],     // シャインマスカット → 夏・秋
            6 => [1, 2],     // パイナップル → 春・夏
            7 => [2, 3],     // ブドウ → 夏・秋
            8 => [2],        // バナナ → 夏
            9 => [1, 2],     // メロン → 春・夏
        ];

        foreach ($products as $index => $productData) {
            $product = Product::create($productData);
            $product->seasons()->attach($seasonMap[$index]);
        }
    }
}

