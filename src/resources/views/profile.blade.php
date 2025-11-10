@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-edit-container">
    <h2 class="profile-title">プロフィール設定</h2>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
        @csrf
        @method('PUT')

        <div class="icon-upload">
            @if(auth()->user()->profile_image)
                <img src="{{ asset('storage/profiles/' . auth()->user()->profile_image) }}" alt="ユーザーアイコン" class="user-icon">
            @else
                <div class="user-icon-placeholder"></div>
            @endif

            <div class="upload-btn-wrapper">
                <input type="file" id="profile_image" name="profile_image" accept="image/*" hidden>
                <button type="button" class="btn-upload" onclick="document.getElementById('profile_image').click()">
                    画像を選択する
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
        </div>

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}">
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" name="building" value="{{ old('building', $user->building) }}">
        </div>

        <button type="submit" class="btn-submit">更新する</button>
    </form>
</div>

@endsection
