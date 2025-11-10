@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-container">
    <h1>住所の変更</h1>

    <form method="POST" action="{{ route('address.update', $item->id) }}">
        @csrf
        @method('PUT')
        <label for="postal_code">郵便番号</label>
        <input id="postal_code" name="postal_code" type="text" value="{{ old('postal_code', $user->postal_code) }}" required/>
        <label for="address">住所</label>
        <input id="address" name="address" type="text" value="{{ old('address', $user->address) }}" required/>
        <label for="building">建物名</label>
        <input id="building" name="building" type="text" value="{{ old('building', $user->building) }}"/>
        <button type="submit" class="update-button">更新する</button>
    </form>
</div>
@endsection