<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 全商品を取得できる()
    {
        if (!Route::has('logout')) {
            Route::post('/logout', function () {
                return redirect('/');
            })->name('logout');
        }

        $user = User::factory()->create();
        $condition = Condition::factory()->create();

        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'user_id' => $user->id,
            'image_path' => 'test.jpg',
            'condition_id' => $condition->id,
            'is_sold' => 0,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
    }

    /** @test */
    public function 購入済み商品は_sold_と表示される()
    {
        if (!Route::has('logout')) {
            Route::post('/logout', function () {
                return redirect('/');
            })->name('logout');
        }

        $user = User::factory()->create();
        $condition = Condition::factory()->create();

        $soldItem = Item::factory()->create([
            'name' => '購入済み商品',
            'user_id' => $user->id,
            'image_path' => 'test.jpg',
            'condition_id' => $condition->id,
            'is_sold' => 1,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        if (!Route::has('logout')) {
            Route::post('/logout', function () {
                return redirect('/');
            })->name('logout');
        }

        $user = User::factory()->create();
        $condition = Condition::factory()->create();

        $myItem = Item::factory()->create([
            'name' => '自分の商品',
            'user_id' => $user->id,
            'image_path' => 'test.jpg',
            'condition_id' => $condition->id,
            'is_sold' => 0,
        ]);

        $otherItem = Item::factory()->create([
            'name' => '他人の商品',
            'user_id' => User::factory()->create()->id,
            'image_path' => 'other.jpg',
            'condition_id' => $condition->id,
            'is_sold' => 0,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee($myItem->name);
        $response->assertSee($otherItem->name);
    }
}
