<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');
        $keyword = $request->input('keyword');
        $query = Item::with('condition');

        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        if ($tab === 'mylist') {
            if (!auth()->check()) {
                $items = collect();
            } else {
                $user = auth()->user();
                $items = $user->likedItems()
                    ->with('condition')
                    ->when($keyword, function ($query, $keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%');
                    })
                    ->get();
            }
        } else {
            $items = $query->latest()->get();
        }

        $items->transform(function ($item) {
            if (!Str::startsWith($item->image_path, 'items/')) {
                $item->image_path = 'items/' . $item->image_path;
            }
            return $item;
        });

        return view('index', compact('items', 'tab', 'keyword'));
    }

    public function show($id)
    {
        $item = Item::with(['categories', 'condition', 'comments', 'likedByUsers'])->findOrFail($id);

        if (!Str::startsWith($item->image_path, 'items/')) {
            $item->image_path = 'items/' . $item->image_path;
        }

        return view('show', compact('item'));
    }

    public function sell()
    {
        return view('sell');
    }

    public function create()
    {
        return view('sell');
    }

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        $validated['condition_id'] = $request->input('condition_id');

        if (!$validated['condition_id']) {
            return back();
        }

        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $filename = $file->store('items', 'public');
            $validated['image_path'] = $filename;
        }


        $validated['user_id'] = Auth::id();
        $validated['is_sold'] = false;

        $item = Item::create($validated);

        if ($request->has('categories')) {
            $categoryIds = \App\Models\Category::whereIn('name', $request->input('categories'))->pluck('id');
            $item->categories()->sync($categoryIds);
        }

        return redirect('/mypage');
    }
}
