<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 必要な情報が取得できる()
    {
        $user = User::factory()->create();

        $itemsForSale = Item::factory()->count(2)->create([
            'user_id' => $user->id,
            'is_sold' => false,
        ]);

        $itemsPurchased = Item::factory()->count(2)->create([
            'user_id' => $user->id,
            'is_sold' => true,
        ]);

        foreach ($itemsPurchased as $item) {
            Order::factory()->create([
                'item_id' => $item->id,
                'user_id' => $user->id,
            ]);
        }

        $response = $this->actingAs($user)->get(route('mypage'));

        $response->assertSee($user->name);

        foreach ($itemsForSale as $item) {
            $response->assertSee($item->name);
        }

        foreach ($itemsPurchased as $item) {
            $response->assertSee($item->name);
        }
    }
}