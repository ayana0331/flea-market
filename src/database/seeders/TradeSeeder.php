<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Item;
use App\Models\User;

class TradeSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::where('email', 'test@example.com')->first();
        $user2 = User::where('email', 'test2@example.com')->first();
        $buyer = User::where('email', 'test3@example.com')->first();

        $items1 = Item::where('user_id', $user1->id)->take(2)->get();
        $items2 = Item::where('user_id', $user2->id)->take(2)->get();

        foreach ($items1->concat($items2) as $item) {
            Order::create([
                'user_id' => $buyer->id,
                'item_id' => $item->id,
                'status' => 'trading',
                'payment_method' => 'card',
            ]);
            $item->update(['is_sold' => true]);
        }
    }
}
