<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $itemId)
    {
        $data = $request->validated();

        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $itemId,
            'body' => $request->body,
        ]);

        return back();
    }
}
