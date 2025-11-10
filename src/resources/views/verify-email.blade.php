<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証</title>
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
</head>

<body>
    <header class="auth-header">
        <img src="{{ asset('images/logo.svg') }}" alt="ロゴ" height="40">
    </header>

    <main class="container">
        <p class="container-text">登録していただいたメールアドレスに認証メールを送付しました。<br>メール認証を完了してください。</p>

        {{-- 認証はこちらから（ボタンに変更） --}}
        <form method="POST" action="{{ route('manual.verify') }}">
            @csrf
            <button type="submit" class="btn btn-primary">認証はこちらから</button>
        </form>

        {{-- 認証メール再送（リンクに変更） --}}
        <a href="{{ route('verification.send') }}" onclick="event.preventDefault(); document.getElementById('resend-form').submit();" class="btn btn-link">認証メールを再送する</a>

        <form id="resend-form" method="POST" action="{{ route('verification.send') }}" style="display: none;">
            @csrf
        </form>
    </main>
</body>
</html>
