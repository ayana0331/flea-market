<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品詳細ページに必要な情報がすべて表示される()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create(['name' => '新品']);
        $categories = Category::factory()->count(2)->sequence(
            ['name' => 'ファッション'],
            ['name' => '家電']
        )->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'price' => 2000,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'image_path' => 'test.jpg',
        ]);

        $item->categories()->attach($categories->pluck('id')->toArray());

        $commentUser = User::factory()->create();
        Comment::factory()->create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'body' => 'テストコメント',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('show', $item));

        $response->assertStatus(200);

        $response->assertSee($item->name);
        $response->assertSee($item->brand);
        $response->assertSee('¥', false);
        $response->assertSee(number_format($item->price), false);
        $response->assertSee($item->description);
        $response->assertSee('test.jpg');
        $response->assertSee('新品');
        $response->assertSee('いいね');
        $response->assertSee('テストコメント');
        $response->assertSee($commentUser->name);
    }

    /** @test */
    public function 複数カテゴリが表示される()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create(['name' => '新品']);
        $categories = Category::factory()->count(2)->sequence(
            ['name' => 'ファッション'],
            ['name' => '家電']
        )->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
        ]);

        $item->categories()->attach($categories->pluck('id')->toArray());

        $this->actingAs($user);
        $response = $this->get(route('show', $item));
        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }
}