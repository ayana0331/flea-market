<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->query('tab', 'listed');

        $items = Item::where('user_id', $user->id)->get();
        $purchasedItems = Item::whereHas('order', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('order')->get();

        $tradingItems = Item::select('items.*')
            ->join('orders', 'items.id', '=', 'orders.item_id')
            ->whereIn('orders.status', ['trading', 'evaluating'])
            ->where(function($query) use ($user) {
                $query->where('orders.user_id', $user->id)
                    ->orWhere('items.user_id', $user->id);
            })
            ->with(['order.messages'])
            ->addSelect(['latest_message_at' => \App\Models\Message::select('created_at')
                ->whereColumn('order_id', 'orders.id')
                ->latest()
                ->limit(1)
            ])
            ->orderByRaw('COALESCE(latest_message_at, orders.created_at) DESC')
            ->get();

        foreach ($tradingItems as $item) {
            if ($item->order && $item->order->messages) {
                $item->unread_count = $item->order->messages
                    ->where('user_id', '!=', $user->id)
                    ->where('is_read', false)
                    ->count();
            } else {
                $item->unread_count = 0;
            }
        }

        $unreadTotal = $tradingItems->sum('unread_count');

        return view('mypage', compact('tab', 'items', 'purchasedItems', 'tradingItems', 'unreadTotal'));
    }

    public function profile()
    {
        return view('profile');
    }
}
