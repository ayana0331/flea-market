@extends('layouts.app')

@section('title', 'マイページ')

@section('head')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<main class="main">
    <div class="user-profile">
        <img src="{{ auth()->user()->profile_image ? asset('storage/profiles/' . auth()->user()->profile_image) : asset('images/default_user.png') }}" alt="ユーザーアイコン" class="user-icon">
        <span class="user-name">{{ auth()->user()->name }}</span>
        @auth
            <a href="{{ route('profile') }}" class="btn-edit-profile">プロフィール編集</a>
        @endauth
    </div>

    <div class="nav-wrapper">
        <div class="nav">
            <a href="{{ url('/mypage?tab=listed') }}" class="{{ $tab === 'listed' ? 'active' : '' }}">出品した商品</a>
            <a href="{{ url('/mypage?tab=purchased') }}" class="{{ $tab === 'purchased' ? 'active' : '' }}">購入した商品</a>
        </div>
    </div>

    <section class="items">
        @php
            $displayItems = match($tab) {
                'listed' => $items->filter(fn($item) => $item->user_id === auth()->id()),
                'purchased' => $purchasedItems ?? collect(),
                default => collect(),
            };
        @endphp

        @forelse($displayItems as $item)
            <div class="item">
                <a href="{{ route('show', $item->id) }}">
                    <div class="image-container">
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
                        @if($item->is_sold)
                            <span class="sold">SOLD</span>
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
