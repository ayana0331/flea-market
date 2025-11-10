<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Like;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねした商品だけが表示される()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();

        $item1 = Item::factory()->create([
            'name' => '他人の商品',
            'condition_id' => $condition->id,
        ]);

        $likedItem = Item::factory()->create([
            'name' => 'いいねした商品',
            'condition_id' => $condition->id,
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('他人の商品');
    }

    /** @test */
    public function 購入済み商品は_SOLD_と表示される()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();

        $soldItem = Item::factory()->create([
            'name' => '購入済み商品',
            'condition_id' => $condition->id,
            'is_sold' => 1,
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $soldItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }

    /** @test */
    public function 未ログインの場合は何も表示されない()
    {
        $condition = Condition::factory()->create();

        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'condition_id' => $condition->id,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('テスト商品');
    }
}
