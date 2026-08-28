<?php

namespace Tests\Feature;

use App\Models\Season;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * 商品登録のバリデーション（要件シート FN009）。
 *
 * 値段は0〜10000円、商品説明は120文字以内、画像はpng/jpegのみ。
 * 境界は「通る側」と「弾かれる側」の両方を押さえる。
 */
class ProductValidationTest extends TestCase
{
    use RefreshDatabase;

    private int $seasonId;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seasonId = Season::forceCreate(['name' => '春'])->id;
    }

    /** 正しい入力一式。上書きしたい項目だけ渡す */
    private function 入力(array $上書き = []): array
    {
        return array_merge([
            'name' => 'いちご',
            'price' => 500,
            'seasons' => [$this->seasonId],
            'description' => '甘くて美味しいいちごです。',
            'image' => UploadedFile::fake()->create('ichigo.jpg', 10, 'image/jpeg'),
        ], $上書き);
    }

    /**
     * @test
     * @dataProvider 値段の境界
     */
    public function 値段は0円から10000円までを受け付ける($price, bool $通る)
    {
        $response = $this->post('/products', $this->入力(['price' => $price]));

        if ($通る) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('products', ['price' => $price]);
        } else {
            $response->assertSessionHasErrors('price');
            $this->assertDatabaseCount('products', 0);
        }
    }

    public static function 値段の境界(): array
    {
        return [
            '下限ちょうど 0円' => [0, true],
            '上限ちょうど 10000円' => [10000, true],
            '範囲内 500円' => [500, true],
            '下限を1つ下回る -1円' => [-1, false],
            '上限を1つ超える 10001円' => [10001, false],
            '数値でない' => ['たかい', false],
        ];
    }

    /**
     * @test
     * @dataProvider 商品説明の境界
     */
    public function 商品説明は120文字までを受け付ける(int $文字数, bool $通る)
    {
        $description = str_repeat('あ', $文字数);

        $response = $this->post('/products', $this->入力(['description' => $description]));

        if ($通る) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('products', ['description' => $description]);
        } else {
            $response->assertSessionHasErrors('description');
        }
    }

    public static function 商品説明の境界(): array
    {
        return [
            '上限ちょうど 120文字' => [120, true],
            '上限を1文字超える 121文字' => [121, false],
            '1文字' => [1, true],
        ];
    }

    /**
     * @test
     * @dataProvider 画像の種類
     */
    public function 画像はpngとjpegだけを受け付ける(string $ファイル名, string $mime, bool $通る)
    {
        // GD拡張が無い環境でも動くよう、実画像ではなくMIME指定のダミーで検証する。
        // アプリの image / mimes ルールはMIMEと拡張子で判定するため、これで十分。
        $image = UploadedFile::fake()->create($ファイル名, 10, $mime);

        $response = $this->post('/products', $this->入力(['image' => $image]));

        if ($通る) {
            $response->assertSessionHasNoErrors();
        } else {
            $response->assertSessionHasErrors('image');
        }
    }

    public static function 画像の種類(): array
    {
        return [
            'jpg は通る' => ['ichigo.jpg', 'image/jpeg', true],
            'png は通る' => ['ichigo.png', 'image/png', true],
            'gif は弾く' => ['ichigo.gif', 'image/gif', false],
            'pdf は弾く' => ['ichigo.pdf', 'application/pdf', false],
        ];
    }

    /** @test */
    public function 必須項目が空だとそれぞれエラーになる()
    {
        $this->post('/products', [])
            ->assertSessionHasErrors(['name', 'price', 'seasons', 'description', 'image']);

        $this->assertDatabaseCount('products', 0);
    }

    /** @test */
    public function 季節は1つ以上選ばないと登録できない()
    {
        $this->post('/products', $this->入力(['seasons' => []]))
            ->assertSessionHasErrors('seasons');
    }

    /** @test */
    public function 商品名は255文字ちょうどなら登録でき256文字は弾かれる()
    {
        $this->post('/products', $this->入力(['name' => str_repeat('あ', 255)]))
            ->assertSessionHasNoErrors();

        $this->post('/products', $this->入力(['name' => str_repeat('あ', 256)]))
            ->assertSessionHasErrors('name');
    }
}
