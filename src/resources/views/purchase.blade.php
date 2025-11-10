@extends('layouts.app')

@section('title', '商品購入')

@section('head')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-wrapper">

    <div class="purchase-left">
        <div class="item-detail">
            <img src="{{ asset('/storage/items/' . $item->image_path) }}" alt="{{ $item->name }}" class="item-image">
            <div class="item-text">
                <h2>{{ $item->name }}</h2>
                <p class="price">¥ {{ number_format($item->price) }}</p>
            </div>
        </div>
        <hr>

        <div class="payment">
            <h3>支払い方法</h3>
            <div class="payment-inner">
                <form action="{{ route('purchase.updatePayment') }}" method="POST" id="paymentForm">
                    @csrf
                    <select id="payment-select" name="payment" onchange="this.form.submit()">
                        <option value="" disabled {{ !session('payment_method') ? 'selected' : '' }}>選択してください</option>
                        <option value="convenience" {{ session('payment_method', 'convenience') === 'convenience' ? 'selected' : '' }}>コンビニ払い</option>
                        <option value="card" {{ session('payment_method') === 'card' ? 'selected' : '' }}>カード支払い</option>
                    </select>
                </form>
            </div>
        </div>
        <hr>

        <div class="address">
            <div class="address-header">
                <h3>配送先</h3>
                <a href="{{ route('address.edit', $item->id) }}" class="btn-change">変更する</a>
            </div>
            <div class="address-inner">
                <p>〒{{ $user->postal_code ?? '未設定' }}</p>
                <p>{{ $user->address ?? '未設定' }} {{ $user->building ?? '' }}</p>
            </div>
        </div>
        <hr>
    </div>

    <div class="purchase-right">
        <div class="summary-box">
            <div class="row">
                <span>商品代金</span>
                <span>¥ {{ number_format($item->price) }}</span>
            </div>
            <hr class="divider">
            <div class="row">
                <span>支払い方法</span>
                <span id="payment-method">
                    @php
                        $method = session('payment_method', 'convenience');
                    @endphp

                    @if ($method === 'card')
                        カード支払い
                    @elseif ($method === 'convenience')
                        コンビニ払い
                    @else
                        未選択
                    @endif
                </span>
            </div>
        </div>

        <form action="{{ route('purchase.checkout', $item->id) }}" method="POST">
            @csrf
            <input type="hidden" name="payment" id="payment-hidden" value="{{ session('payment_method', 'convenience') }}">
            <button type="submit" class="btn-purchase">購入する</button>
        </form>
    </div>
</div>
@endsection
