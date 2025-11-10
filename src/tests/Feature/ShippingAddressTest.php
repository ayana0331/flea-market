<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 住所変更後に購入画面に反映される()
    {
        $user = User::factory()->create();

        $user->update([
            'postal_code' => '111-1111',
            'address'     => '東京都千代田区',
            'building'    => 'テストビル101',
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('purchase.show', $item));
        $response->assertStatus(200);
        $response->assertSee($user->postal_code);
        $response->assertSee($user->address);
        $response->assertSee($user->building);
    }

    /** @test */
    public function 購入した商品に住所が紐づいて登録される()
    {
        $user = User::factory()->create();

        $user->update([
            'postal_code' => '111-1111',
            'address'     => '東京都千代田区',
            'building'    => 'テストビル101',
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('purchase.show', $item));
        $response->assertStatus(200);

        $response->assertSee('111-1111');
        $response->assertSee('東京都千代田区');
        $response->assertSee('テストビル101');
    }
}