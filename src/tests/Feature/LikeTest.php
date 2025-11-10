<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねした商品として登録されいいね合計値が増加する()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('items.like', $item));

        $response->assertStatus(302);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function いいねアイコンが押下された状態では色が変化する()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->post(route('items.like', $item));

        $response = $this->get("/items/{$item->id}");

        $response->assertSee('star_icon_1.png');
        $response->assertDontSee('star_icon_0.png');
    }

    /** @test */
    public function いいねが解除されいいね合計値が減少表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->post(route('items.like', $item));

        $this->post(route('items.like', $item));

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get("/items/{$item->id}");
        $response->assertSee('star_icon_0.png');
        $response->assertDontSee('star_icon_1.png');
    }
}