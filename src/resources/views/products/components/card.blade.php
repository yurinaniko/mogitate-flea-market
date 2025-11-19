<a href="{{ route('products.edit', $product->id) }}" class="product-card">
    <div class="product-card__image">
        <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}">
    </div>

    <div class="product-card__content">
        <h3 class="product-card__title">{{ $product->name }}</h3>
        <p class="product-card__price">¥{{ number_format($product->price) }}</p>
    </div>
</a>