@extends('layouts.app')

@section('title', 'マイページ')

@section('head')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<main class="main">
    <div class="user-profile">
        <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default_user.png') }}" alt="ユーザーアイコン" class="user-icon">
        <div class="user-info-text">
            <span class="user-name">{{ auth()->user()->name }}</span>

            @php
                $avg = auth()->user()->averageRating();
                $rating = $avg ? (int)round($avg) : 0;
            @endphp

            @if($avg)
                <div class="user-rating">
                    {{ str_repeat('★', $rating) }}{{ str_repeat('☆', 5 - $rating) }}
                </div>
            @else
                <div class="user-rating" style="color: #D9D9D9">
                    ☆☆☆☆☆
                </div>
            @endif
        </div>

        @auth
            <a href="{{ route('profile') }}" class="btn-edit-profile">プロフィール編集</a>
        @endauth
    </div>

    <div class="nav-wrapper">
        <div class="nav">
            <a href="{{ url('/mypage?tab=listed') }}" class="{{ $tab === 'listed' ? 'active' : '' }}">出品した商品</a>
            <a href="{{ url('/mypage?tab=purchased') }}" class="{{ $tab === 'purchased' ? 'active' : '' }}">購入した商品</a>
            <a href="{{ url('/mypage?tab=trading') }}" class="{{ $tab === 'trading' ? 'active' : '' }}">取引中の商品
                @if(($unreadTotal ?? 0) > 0)
                    <span class="nav-badge">{{ $unreadTotal }}</span>
                @endif
            </a>
            @if($tab === 'trading')
                @if(($item->unread_count ?? 0) > 0)
                    <span class="notification-badge">{{ $item->unread_count }}</span>
                @endif
            @endif
        </div>
    </div>

    <section class="items">
        @php
            $displayItems = match($tab) {
                'listed' => $items->filter(fn($item) => $item->user_id === auth()->id()),
                'purchased' => $purchasedItems ?? collect(),
                'trading' => $tradingItems ?? collect(),
                default => collect(),
            };
        @endphp

        @forelse($displayItems as $item)
            @php
                if ($tab === 'trading') {
                    $linkUrl = route('chat', $item->order->id);
                } else {
                    $linkUrl = route('show', $item->id);
                }
            @endphp

            <div class="item">
                <a href="{{ $linkUrl }}">
                    <div class="image-container">
                        <img src="{{ asset('storage/items/' . $item->image_path) }}" alt="{{ $item->name }}">
                        @if($tab === 'trading')
                            @if(($item->unread_count ?? 0) > 0)
                                <span class="notification-badge">{{ $item->unread_count }}</span>
                            @endif
                        @else
                            @if($item->is_sold)
                                <span class="sold">SOLD</span>
                            @endif
                        @endif
                    </div>
                    <p class="item-name">{{ $item->name }}</p>
                </a>
            </div>
        @empty
            <p>表示する商品はありません。</p>
        @endforelse
    </section>
</main>
@endsection
