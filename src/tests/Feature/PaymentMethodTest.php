<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;


class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 支払い方法を選択すると画面に反映される()
    {
        $user = User::factory()->create(['name' => 'テスト太郎']);
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get('/purchase/confirm?item_id=' . $item->id . '&payment=card');

        $response->assertSee('カード支払い');
    }
}
