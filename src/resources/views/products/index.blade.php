@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/product-list.css') }}">
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
@endsection

@section('content')
@if (session('success'))
    <div class="toast">{{ session('success') }}</div>
@endif
@if (session('delete'))
    <div class="toast toast--delete">{{ session('delete') }}</div>
@endif
<div class="product-list__main">
    {{-- ====== ヘッダー ====== --}}
    <div class="product-list__header">
        @if ($keyword)
        <h2 class="search-result-title">"{{ $keyword }}"の商品一覧</h2>
        @else
        <h2 class="page-title">商品一覧</h2>
        @endif
            <div class="product-list__header-right">
                <a href="{{ route('products.create') }}" class="product-add-btn"> + 商品を追加</a>
            </div>
    </div>
    {{-- ====== 左右2カラム ====== --}}
    <div class="product-list__container">
        <section class="search-section">
            <div class="search-box">
                <form action="{{ route('products.index') }}" method="GET" class="search-form"  novalidate autocomplete="off">
                    <div class="search-form__group">
                        <input type="text" name="keyword" class="search-form__input" placeholder="商品名で検索" value="{{ request('keyword') }}" autocomplete="off" inputmode="search">
                    </div>
                    <div class="search-form__actions">
                        <button type="submit" class="search-form__button">検索</button>
                    </div>
                    {{-- 並び替え --}}
                    <div class="search-form__group mt-20">
                        <label class="search-form__label">価格順で表示</label>
                        <select name="sort" class="search-form__select {{ $sort ? 'not-empty' : '' }}"
                        onchange="this.form.submit()">
                            <option value="" {{ $sort === null ? 'selected' : '' }}>価格で並び替え</option>
                            <option value="high" {{ $sort == 'high' ? 'selected' : '' }}>高い順に表示</option>
                            <option value="low" {{ $sort == 'low' ? 'selected' : '' }}>低い順に表示</option>
                        </select>
                    </div>
                    {{-- ▼ 並び替え解除ボタン ▼ --}}
                    @if(isset($sort) && $sort !== '')
                    <div class="order-reset">
                        @if ($sort === 'high')
                            <a href="{{ route('products.index', ['keyword' => $keyword]) }}" class="btn-reset">
                            高い順に表示
                            <span class="reset-icon">×</span>
                            </a>
                        @elseif ($sort === 'low')
                            <a href="{{ route('products.index', ['keyword' => $keyword]) }}" class="btn-reset">
                            低い順に表示
                            <span class="reset-icon">×</span>
                            </a>
                        @endif
                    </div>
                    @endif
                </form>
            </div>
        </section>
        {{-- 商品リスト --}}
        <div class="product-list">
            @forelse($products as $product)
                @include('products.components.card', ['product' => $product])
            @empty
                <p class="no-product">該当する商品はありません。</p>
            @endforelse
        </div>
    </div>
    {{-- ===== ページネーション ===== --}}
    <div class="pagination-wrapper">
        {{ $products->appends(['keyword' => $keyword, 'sort' => $sort])->links('components.pagination') }}
    </div>
</div>
@endsection
