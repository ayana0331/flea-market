<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログインユーザーは商品を購入できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false,]);

        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $item->update(['is_sold' => true]);

        $this->assertEquals(1, $item->fresh()->is_sold);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function 購入済み商品は商品一覧で_sold_と表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => true]);

        $response = $this->actingAs($user)
            ->get('/');

        $response->assertSee('SOLD');
    }

    /** @test */
    public function 購入した商品はプロフィール購入一覧に追加される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => true]);

        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get('/mypage?tab=purchased');

        $response->assertSee($item->name);
    }
}
