<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyApp')</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/app.css') }}">
    @yield('head')
</head>
<body>
<header class="header">
    <a href="{{ url('/') }}">
        <img src="{{ asset('images/logo.svg') }}" alt="ロゴ" height="40">
    </a>

    <div class="search">
        <form action="{{ route('index') }}" method="GET">
            <input type="text" name="keyword" class="search-input" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
        </form>
    </div>

    <nav class="header-nav">
        @auth
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="btn-link">ログアウト</button>
            </form>
            <a href="{{ route('mypage') }}">マイページ</a>
            <a href="{{ route('items.create') }}" class="btn-exhibit">出品</a>
        @else
            <a href="{{ route('login') }}">ログイン</a>
            <a href="{{ route('login') }}">マイページ</a>
            <a href="{{ route('login') }}" class="btn-exhibit">出品</a>
        @endauth
    </nav>
</header>

@yield('content') {{-- 各ページのメイン部分 --}}
</body>
</html>
