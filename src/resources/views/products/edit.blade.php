@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/product-list.css') }}">
<link rel="stylesheet" href="{{ asset('css/product-edit.css') }}">
@endsection

@section('content')
<div class="product-edit">
    <div class="product-edit__title">
        <a href="{{ route('products.index') }}" class="return-link">商品一覧</a>
        <span> > {{ $product->name }}</span>
    </div>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="product-edit__form">
        @csrf
        @method('PUT')
        <input type="hidden" id="delete_image" name="delete_image" value="0">
        <div class="product-edit__wrapper">
            <div class="product-edit__left">
                <div class="product-edit__image-area">
                    @if(old('delete_image'))
                        <img id="preview-image" src="" style="display:none;">
                    @else
                        <img id="preview-image" src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}">
                    @endif
                </div>
                @error('image')
                    <p class="form__error product-edit__image-error">{{ $message }}</p>
                @enderror
                    <label class="product-edit__file-label">
                    ファイルを選択
                        <input type="file" name="image" id="image" class="product-edit__file" accept="image/*" onchange="previewImage(event)">
                    </label>
            </div>
            <div class="product-edit__right">
                <div class="form-group">
                    <div class="form-row">
                        <label class="form-label">商品名</label>
                        <input type="text" name="name" class="form-input" value="{{ old('name', $product->name) }}" placeholder="商品名を入力">
                        @error('name')
                            <p class="form__error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label class="form-label">値段</label>
                        <input type="text" name="price" class="form-input" value="{{ old('price', $product->price) }}" placeholder="値段を入力">
                        @error('price')
                            <p class="form__error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <label class="form-label">季節</label>
                    </div>
                    <div class="seasons-group">
                        @php
                            if ($errors->has('seasons')) {
                                $checkedSeasons = old('seasons', []);
                            } else {
                                $checkedSeasons = old('seasons', $product->seasons->pluck('id')->toArray());
                            }
                        @endphp
                        @foreach ($seasons as $season)
                            <label class="season-option">
                                <input type="checkbox" name="seasons[]" value="{{ $season->id }}"
                                {{ in_array($season->id, $checkedSeasons) ? 'checked' : '' }}>
                                <span>{{ $season->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('seasons')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-row description-row">
            <div class="form-row">
                <label class="form-label">商品説明</label>
                <textarea name="description" class="form-textarea" rows="6" placeholder="商品の説明を入力し てください">{{ old('description', $product->description ?? '') }}</textarea>
                @error('description')
                    <p class="form__error">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="product-edit__buttons">
            <a href="{{ route('products.index') }}" class="button button__back">戻る</a>
            <button type="submit" class="button button__save">変更を保存</button>
        </div>
    </form>
    <div class="product-edit__delete">
        <details class="modal">
            <summary class="modal-open">
                <img src="{{ asset('images/Vector.png') }}" alt="削除" class="product-delete__icon">
            </summary>
            <div class="modal-content">
                <p>何を削除しますか？</p>
                    <button type="button" class="modal-delete-image" onclick="deleteImageOnly()">
                        画像だけ削除
                    </button>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="modal-delete-product">商品を削除</button>
                    </form>
                    <button type="button" class="modal-close" onclick="this.closest('details').removeAttribute('open')">
                    キャンセル
                    </button>
            </div>
        </details>
    </div>
</div>
<script>
    function previewImage(event) {
        const preview = document.getElementById('preview-image');
        const file = event.target.files[0];

        if (file) {
            document.getElementById('delete_image').value = 0;
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.style.opacity = '1';
            };
            reader.readAsDataURL(file);
        }
    }

    function deleteImageOnly() {
        document.getElementById('delete_image').value = 1;
        const preview = document.getElementById('preview-image');
        const fileInput = document.querySelector('input[type="file"]');
            preview.style.display = 'none';
            preview.src = '';
            fileInput.value = '';
    }
</script>
@endsection