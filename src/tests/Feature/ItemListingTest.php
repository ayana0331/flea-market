<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 出品商品情報登録()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $itemData = [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト用の商品説明',
            'price' => 1000,
            'condition_id' => 1,
            'category_id' => 1,
        ];

        $response = $this->post('/sell', $itemData);

        $response->assertStatus(302);

        $listResponse = $this->get('/');
        $listResponse->assertStatus(200);
        $listResponse->assertSee('テスト商品');
        $listResponse->assertSee('テストブランド');
        $listResponse->assertSee('テスト用の商品説明');
        $listResponse->assertSee('1000');
    }
}