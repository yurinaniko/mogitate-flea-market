<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /** @test */
    public function トップページは商品一覧へリダイレクトする()
    {
        $this->get('/')->assertRedirect('/products');
    }

    /** @test */
    public function 商品一覧が表示できる()
    {
        $this->get('/products')->assertStatus(200);
    }
}
