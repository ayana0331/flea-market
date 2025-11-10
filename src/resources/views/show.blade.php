@extends('layouts.app')

@section('title', '商品詳細')

@section('content')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
<div class="show-main">
    <div class="show-image">
        <div class="image-container">
            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
            @if($item->is_sold)
                <span class="sold">SOLD</span>
            @endif
        </div>
    </div>

    <div class="show-info">
        <div class="title">{{ $item->name }}</div>
        <div class="brand">{{ $item->brand }}</div>
        <div class="price">
            <span class="price-yen">¥</span>{{ number_format($item->price) }}<span class="price-tax">（税込）</span>
        </div>

        <div class="buttons">
            <div class="top-buttons">
                <form action="{{ route('items.like', $item) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="like-button">
                        @if($item->isLikedBy(auth()->user()))
                            <img src="{{ asset('images/star_icon_1.png') }}" alt="いいね済み" width="40" height="40">
                        @else
                            <img src="{{ asset('images/star_icon_0.png') }}" alt="いいね" width="40" height="40">
                        @endif
                    </button>
                </form>

                <button class="comment-button">
                    <img src="{{ asset('images/comment_icon_0.png') }}" alt="コメント" width="40" height="40">
                </button>

                <div class="counts">
                    <p>{{ $item->likedByUsers()->count() }}</p>
                    <p>{{ $item->comments()->count() }}</p>
                </div>
            </div>

            <div class="purchase-section">
                @if($item->is_sold)
                    <button class="buy-button sold-button" disabled>SOLD</button>
                @else
                    <a href="{{ route('purchase.create', $item->id) }}" class="buy-button">購入手続きへ</a>
                @endif
            </div>
        </div>


        <div class="description-section">
            <h3 class="description-title">商品説明</h3>
            <div class="description">{{ $item->description }}</div>
        </div>

        <div class="product-info-section">
            <h3>商品の情報</h3>
            <div class="category-section info-row">
                <div>カテゴリー</div>
                <div class="category-list">
                    @foreach ($item->categories as $category)
                        <span class="category-item">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>

            <div class="condition-section info-row">
                <div>商品の状態</div>
                <div class="condition">
                    <p>{{ $item->condition->name }}</p>
                </div>
            </div>
        </div>

        <div class="comments-header">
            <h4>コメント ({{ $item->comments->count() }})</h4>
        </div>

        <div class="comments-list">
            @foreach ($item->comments as $comment)
                <div class="comment">
                    <img src="{{ asset('storage/profiles/' . $comment->user->profile_image) }}" alt="{{ $comment->user->name }}">
                    <div class="comment-user">{{ $comment->user->name }}</div>
                    <div class="comment-text">{{ $comment->body }}</div>
                </div>
            @endforeach
        </div>


        <div class="comment-form-header">
            <h4>商品へのコメント</h4>
        </div>

        <form action="{{ route('comments.store', $item) }}" method="POST" class="comment-form">
            @csrf
            <textarea name="body"></textarea>
            @error('body')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <button type="submit">コメントを送信する</button>
        </form>

    </div>
</div>
@endsection
