<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Order;

class ChatController extends Controller
{
    public function index($order_id)
    {
        $order = Order::with(['item.user', 'user', 'messages.user'])->findOrFail($order_id);

        $order->messages()
        ->where('user_id', '!=', auth()->id())
        ->where('is_read', false)
        ->update(['is_read' => true]);

        $user = auth()->id();
        $otherOrders = Order::where('status', 'trading')->where(function($q) use ($user) {
            $q->where('user_id', $user)->orWhereHas('item', function($query) use ($user) {
                $query->where('user_id', $user);
                });
            })->where('id', '!=', $order_id)->get();

        return view('chat', compact('order', 'otherOrders'));
    }

    public function store(MessageRequest $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('messages', 'public');
        }

        Message::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'image_path' => $path,
        ]);

        return back()->with('message', 'メッセージを送信しました');
    }

    public function complete($order_id)
    {
        $order = Order::findOrFail($order_id);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->update(['status' => 'evaluating']);

        $order->item->update(['is_sold' => true]);

        return back()->with('message', '取引を完了しました。出品者を評価してください。');
    }

    public function update(Request $request, $message_id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::findOrFail($message_id);

        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        $message->update([
            'content' => $request->content
        ]);

        return back()->with('message', 'メッセージを更新しました');
    }

    public function destroy($message_id)
    {
        $message = \App\Models\Message::findOrFail($message_id);

        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        if ($message->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($message->image_path);
        }

        $message->delete();

        return back()->with('message', 'メッセージを削除しました');
    }
}
