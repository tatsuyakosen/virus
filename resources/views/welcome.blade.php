<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>商品管理システム</title>
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f3f4f6;
            margin: 0;
            flex-direction: column;
            text-align: center;
        }
        .title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .description {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }
        .auth-buttons {
            display: flex;
            gap: 20px;
        }
        .auth-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .auth-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="title">商品管理システム</div>
    <div class="description">このサイトは商品管理用のシステムで、商品情報の登録、更新、削除、検索が行えます。</div>
    
    <div class="auth-buttons">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/product') }}" class="auth-button">商品一覧へ</a>
            @else
                <a href="{{ route('login') }}" class="auth-button">ログイン</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="auth-button">新規登録</a>
                @endif
            @endauth
        @endif
    </div>
</body>
</html>
