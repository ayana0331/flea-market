@extends('layouts.app')

@section('title', '商品一覧')

@section('head')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<main class="main">
    <div class="nav-wrapper">
        <div class="nav">
            <a href="{{ url('/') }}" class="{{ $tab !== 'mylist' ? 'active' : '' }}">おすすめ</a>
            <a href="{{ url('/?tab=mylist' . (request('keyword') ? '&keyword=' . urlencode(request('keyword')) : '')) }}" class="{{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
        </div>
    </div>
    <section class="items">
        @php
            $displayItems = $tab === 'mylist' && auth()->check()
                ? $items->filter(fn($item) => $item->isLikedBy(auth()->user()))
                : $items->filter(fn($item) => $item->user_id !== auth()->id());
        @endphp

        @forelse($displayItems as $item)
            <div class="item">
                <a href="{{ route('show', $item->id) }}" class="item-link">
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
