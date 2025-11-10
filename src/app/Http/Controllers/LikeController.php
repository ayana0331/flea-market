<?php

namespace App\Http\Controllers;

use App\Models\Item;

class LikeController extends Controller
{
    public function toggle(Item $item)
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($item->isLikedBy($user)) {
            $user->likedItems()->detach($item->id);
        } else {
            $user->likedItems()->attach($item->id);
        }

        return back();
    }
}
