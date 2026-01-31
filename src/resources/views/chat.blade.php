<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>取引チャット - {{ $order->item->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
</head>
<body>
    <header class="header">
        <a href="/"><img src="{{ asset('images/logo.svg') }}" alt="ロゴ" height="40"></a>
    </header>

    <div class="chat-container">
        <aside class="sidebar">
            <h3>その他の取引</h3>
            @foreach($otherOrders as $other)
                <a href="{{ route('chat', $other->id) }}" class="sidebar-item">
                    <div>
                        <div class="sidebar-item-txt">{{ $other->item->name }}</div>
                    </div>
                </a>
            @endforeach
        </aside>

        <main class="main-content">
            @php
                $partner = (auth()->id() === $order->user_id) ? $order->item->user : $order->user;
            @endphp
            <div class="main-header">
                <div class="user-info">
                    <img src="{{ $partner->profile_image ? asset('storage/' . $partner->profile_image) : asset('images/default_user.png') }}" class="user-icon">
                    <h2 class="user-title">「{{ $partner->name }}」さんとの取引画面</h2>
                </div>
                @if(auth()->id() === $order->user_id && $order->status === 'trading')
                    <form action="{{ route('order.complete', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-complete">取引を完了する</button>
                    </form>
                @endif
            </div>

            <div class="item-bar">
                <img src="{{ asset('storage/items/' . $order->item->image_path) }}" class="item-img">
                <div class="item-details">
                    <div class="item-name">{{ $order->item->name }}</div>
                    <div class="item-price">&yen;{{ number_format($order->item->price) }}</div>
                </div>
            </div>

            <div class="message-area">
                @foreach($order->messages as $msg)
                    <div class="message-block {{ $msg->user_id === auth()->id() ? 'is-me' : '' }}">
                        <div class="message-user">
                            <img src="{{ $msg->user->profile_image ? asset('storage/' . $msg->user->profile_image) : asset('images/default_user.png') }}" class="msg-user-icon">
                            {{ $msg->user->name }}
                        </div>
                        <div class="message-text-wrapper">
                            <div class="message-text" id="text-{{ $msg->id }}">{{ $msg->content }}</div>
                            <form action="{{ route('chat.update', $msg->id) }}" method="POST" id="edit-form-{{ $msg->id }}" style="display: none;">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="content" value="{{ $msg->content }}" class="input-text">
                                <div class="btn-edit-mode">
                                    <button type="submit">保存</button>
                                    <button type="button" onclick="cancelEdit({{ $msg->id }})">キャンセル</button>
                                </div>
                            </form>
                            @if($msg->image_path)
                                <div class="message-image">
                                    <img src="{{ asset('storage/' . $msg->image_path) }}">
                                </div>
                            @endif
                            @if($msg->user_id === auth()->id())
                                <div class="message-actions" id="actions-{{ $msg->id }}">
                                    <button type="button" class="btn-edit-mode" onclick="editMessage({{ $msg->id }})">編集
                                    </button>
                                    <form action="{{ route('chat.destroy', $msg->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-edit-mode">削除
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($errors->any())
                <div class="error-messages" style="padding: 0px 25px;">
                    @foreach ($errors->all() as $error)
                        <p style="color: #FF5555; font-weight: bold; margin: 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('chat.store', $order->id) }}" method="POST" enctype="multipart/form-data" class="input-form">
                @csrf
                <input type="text" name="content" class="input-text" placeholder="取引メッセージを記入してください">
                <label class="image-select-btn">
                    <span>画像を追加</span>
                    <input type="file" name="image" style="display: none;" onchange="previewFileName(this)">
                </label>
                <button type="submit" class="send-btn">
                    <img src="{{ asset('images/icon-send.jpg') }}" alt="送信">
                </button>
            </form>
        </main>
    </div>
    <script>
    function editMessage(id) {
        document.getElementById('text-' + id).style.display = 'none';
        document.getElementById('actions-' + id).style.display = 'none';
        document.getElementById('edit-form-' + id).style.display = 'block';
    }

    function cancelEdit(id) {
        document.getElementById('text-' + id).style.display = 'inline-block';
        document.getElementById('actions-' + id).style.display = 'block';
        document.getElementById('edit-form-' + id).style.display = 'none';
    }
    </script>

    @php
        $isBuyer = auth()->id() === $order->user_id;
        $isSeller = auth()->id() === $order->item->user_id;
        $myReviewExists = \App\Models\Review::where('order_id', $order->id)->where('user_id', auth()->id())->exists();
        $opponentReviewExists = \App\Models\Review::where('order_id', $order->id)->where('user_id', '!=', auth()->id())->exists();
    @endphp

    @if(!$myReviewExists)
        @php
            $showModal = false;
            $title = "";
            if ($isBuyer && $order->status === 'evaluating' && !$myReviewExists) {
                $showModal = true;
                $title = "出品者を評価して取引を完了してください";
            } elseif ($isSeller && $order->status === 'evaluating' && $opponentReviewExists && !$myReviewExists) {
                $showModal = true;
                $title = "購入者の評価をしてください";
            }
        @endphp
        @if($showModal)
            <div class="modal-overlay">
                <div class="review-modal">
                    <h2 class="modal-header-ttl">取引が完了しました。</h2>
                    <hr class="modal-divider">
                    <form action="{{ route('review.store', $order->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p class="modal-body-txt">今回の取引相手はどうでしたか？</p>
                            <div class="star-rating">
                                <input type="radio" name="rating" value="5" id="star5" required><label for="star5">★</label>
                                <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                                <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                                <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                                <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
                            </div>
                        </div>
                        <hr class="modal-divider">
                        <div class="modal-footer">
                            <button type="submit" class="btn-review-submit">送信する</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif
</body>
</html>