<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Str;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'listed');

        $items = Item::all();
        $purchasedItems = auth()->user()->purchasedItems ?? collect();

        $items->transform(function ($item) {
            if (!Str::startsWith($item->image_path, 'items/')) {
                $item->image_path = 'items/' . $item->image_path;
            }
            return $item;
        });

        $purchasedItems->transform(function ($item) {
            if (!Str::startsWith($item->image_path, 'items/')) {
                $item->image_path = 'items/' . $item->image_path;
            }
            return $item;
        });

        return view('mypage', compact('tab', 'items', 'purchasedItems'));
    }

    public function profile()
    {
        return view('profile');
    }
}
