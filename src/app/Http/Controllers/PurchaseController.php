<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function create(Item $item)
    {
        $user = auth()->user()->fresh();
        return view('purchase', compact('item', 'user'));
    }

    public function checkout(Request $request, Item $item)
    {
        $validated = $request->validate([
            'payment' => 'required|string|in:card,convenience',
        ]);

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $user = auth()->user();
        $address = $user->address()->latest('updated_at')->first();

        if (!$address) {
            return back();
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            if ($validated['payment'] === 'card') {
                $session = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'jpy',
                            'product_data' => [
                                'name' => $item->name,
                            ],
                            'unit_amount' => $item->price,
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('purchase.success', ['item' => $item->id]),
                    'cancel_url' => url('/'),
                ]);
            } else {
                $session = Session::create([
                    'payment_method_types' => ['konbini'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'jpy',
                            'product_data' => [
                                'name' => $item->name,
                            ],
                            'unit_amount' => $item->price,
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('purchase.success', ['item' => $item->id]),
                    'cancel_url' => url('/'),
                ]);
            }

            return redirect($session->url);

        } catch (\Exception $e) {
            \Log::error('Stripe Error: ' . $e->getMessage());
            return back();
        }
    }

    public function success(Item $item)
    {
        $user = auth()->user();
        $address = $user->address()->latest('updated_at')->first();

        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
            'payment_method' => 'card',
        ]);

        $item->update(['is_sold' => true]);

        return redirect('/');
    }

    public function show(Item $item)
    {
        $user = auth()->user();
        return view('purchase', compact('item','user'));
    }

    public function updatePaymentMethod(Request $request)
    {
        $request->validate([
            'payment' => 'required|string|in:convenience,card',
        ]);

        session(['payment_method' => $request->payment]);

        return back();
    }
}
