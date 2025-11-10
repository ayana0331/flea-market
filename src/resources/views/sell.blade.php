@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-container">
    <h1>商品の出品</h1>

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 商品画像 --}}
        <div class="form-group">
            <label for="image_path">商品画像</label>
            <div class="custom-file-upload">
                <input type="file" name="image_path" id="image_path" accept="image/*" required>
                <button type="button" class="btn-upload" onclick="document.getElementById('image_path').click()">
                    画像を選択する
                </button>
            </div>
        </div>

        <h2 class="section-title">商品の詳細</h2>

        {{-- カテゴリー --}}
        <div class="form-group">
            <label>カテゴリー</label>
            <div class="category-buttons">
                @foreach(['ファッション','家電','インテリア','レディース','メンズ','コスメ','本','ゲーム','スポーツ','キッチン','ハンドメイド','アクセサリー','おもちゃ','ベビー・キッズ'] as $category)
                    <label class="category-option">
                        <input type="checkbox" name="categories[]" value="{{ $category }}">
                        <span>{{ $category }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label for="condition_id">商品の状態</label>
            <select name="condition_id" id="condition_id" required>
                <option value="" disabled selected>選択してください</option>
                <option value="1">良好</option>
                <option value="2">目立った汚れや傷なし</option>
                <option value="3">やや傷や汚れあり</option>
                <option value="4">状態が悪い</option>
            </select>
        </div>

        <h2 class="section-title">商品名と説明</h2>
        <div class="form-group">
            <label for="name">商品名</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="brand">ブランド名</label>
            <input type="text" name="brand" id="brand" value="{{ old('brand') }}">
        </div>

        <div class="form-group">
            <label for="description">商品の説明</label>
            <textarea name="description" id="description" rows="5" required>{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="price">販売価格</label>
            <div class="price-input-wrapper">
                <span class="price-symbol">¥</span>
                <input type="text" name="price" id="price" value="{{ old('price') }}" required>
            </div>
        </div>

        <button type="submit" class="btn-submit">出品する</button>
    </form>
</div>
@endsection
