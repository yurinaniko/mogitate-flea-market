@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/product-create.css') }}">
@endsection

@section('content')
<div class="product-create">
    <h2 class="product-create__title">商品登録</h2>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="product-create__form">
        @csrf
        <div class="form-group">
            <div class="form-row">
                <label class="form-label">商品名<span class="form-required">必須</span></label>
            </div>
            <input type="text" name="name" class="form-input" placeholder="商品名を入力" value="{{ old('name') }}">
            @error('name')
                <p class="form__error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <div class="form-row">
                <label class="form-label">値段<span class="form-required">必須</span></label>
            </div>
            <input type="text" name="price" class="form-input" placeholder="値段を入力" value="{{ old('price') }}">
            @error('price')
                <p class="form__error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <div class="form-row">
                <label class="form-label">商品画像<span class="form-required">必須</span></label>
            </div>
            <div class="image-preview-wrapper">
                <img id="preview-image" src="" class="image-preview" style="display:none;">
            </div>
            <input type="file" name="image" accept="image/*" class="form-file" onchange="previewFile(this)">
            @error('image')
                <p class="form__error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <div class="form-row">
                <label class="form-label">季節<span class="form-required">必須</span><span class="form-note">(複数選択可)</span></label>
            </div>
            <div class="seasons-group">
                @foreach ($seasons as $season)
                    <label class="season-option">
                    <input type="checkbox" name="seasons[]" value="{{ $season->id }}"
                    {{ in_array($season->id, old('seasons', [])) ? 'checked' : '' }}>
                    <span>{{ $season->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('seasons')
                <p class="form__error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <div class="form-row">
                <label class="form-label">商品説明<span class="form-required">必須</span></label>
            </div>
            <textarea name="description" class="form-textarea" placeholder="商品の説明を入力" rows="6">{{ old('description') }}</textarea>
            @error('description')
                <p class="form__error">{{ $message }}</p>
            @enderror
        </div>
        <div class="product-create__buttons">
            <a href="{{ route('products.index') }}" class="button button__back">戻る</a>
            <button type="submit" class="button button__save">登録</button>
        </div>
    </form>
</div>
<script>
    function previewFile(input) {
        const file = input.files[0];
        const preview = document.getElementById('preview-image');

        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
        preview.src = '';
        preview.style.display = 'none';
        }
    }
</script>
@endsection
