<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mogitate</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product-list.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600&display=swap" rel="stylesheet">
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__logo">
                <a href="{{ route('products.index') }}">mogitate</a>
            </div>
        </div>
    </header>
    @yield('content')
</body>
</html>
