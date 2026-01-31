<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Review;
use App\Mail\ReviewNotification;
use Illuminate\Support\Facades\Mail;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $targetUserId = (auth()->id() === $order->user_id)
                    ? $order->item->user_id
                    : $order->user_id;

        Review::create([
            'order_id'       => $order->id,
            'user_id'        => auth()->id(),
            'target_user_id' => $targetUserId,
            'rating'         => $request->rating,
        ]);

        if (auth()->id() === $order->user_id) {
            $seller = $order->item->user;
            Mail::to($seller->email)->send(new ReviewNotification($order));
        }

        $reviewCount = Review::where('order_id', $order->id)->count();

        if ($reviewCount >= 2) {
            $order->update(['status' => 'completed']);
        }

        return back()->with('success', '評価を投稿しました！');
    }
}
