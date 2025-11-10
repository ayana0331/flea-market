<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Condition;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        $condition = Condition::factory()->create();

        $item1 = Item::factory()->create([
            'name' => 'テスト商品A',
            'condition_id' => $condition->id,
        ]);

        $item2 = Item::factory()->create([
            'name' => 'サンプル商品B',
            'condition_id' => $condition->id,
        ]);

        $response = $this->get('/?keyword=テスト');

        $response->assertStatus(200);
        $response->assertSee('テスト商品A');
        $response->assertDontSee('サンプル商品B');
    }

    /** @test */
    public function マイリストでも検索状態が保持されている()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();

        $likedItem = Item::factory()->create([
            'name' => 'テスト商品C',
            'condition_id' => $condition->id,
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist&keyword=テスト');

        $response->assertStatus(200);
        $response->assertSee('テスト商品C');
        $response->assertSee('value="テスト"', false);
    }
}