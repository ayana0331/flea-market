<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;


class AddressController extends Controller
{
    public function edit(Item $item)
    {
        $user = auth()->user();
        return view('address', compact('user', 'item'));
    }

    public function update(Request $request, Item $item)
{
    $data = $request->validate([
        'postal_code' => 'required|string|max:10',
        'address' => 'required|string|max:255',
        'building' => 'nullable|string|max:255',
    ]);

    auth()->user()->update($data);

    return redirect()->route('purchase.show', $item->id);
    }
}
