<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログイン済みユーザーはコメントを送信できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comments.store', $item->id), [
            'body' => 'テストコメントです',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body' => 'テストコメントです',
        ]);
    }

    /** @test */
    public function 未ログインユーザーはコメントを送信できない()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('comments.store', $item->id), [
            'body' => 'テストコメント',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('comments', [
            'body' => 'テストコメント',
        ]);
    }

    /** @test */
    public function コメントが入力されていない場合はバリデーションエラー()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comments.store', $item->id), [
            'body' => '',
        ]);

        $response->assertSessionHasErrors('body');
    }

    /** @test */
    public function コメントが255文字以上の場合はバリデーションエラー()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $longComment = str_repeat('a', 256);

        $response = $this->post(route('comments.store', $item->id), [
            'body' => $longComment,
        ]);

        $response->assertSessionHasErrors('body');
    }
}