<div class="cart-items " >
    <a href="javascript:void(0)" class="main-btn">
        <i class="lni lni-heart"></i>
        <span class="total-items">{{ $wishlists->count() }}</span>
    </a>
    <!-- Shopping Item -->
    <div class="shopping-item">
        <div class="dropdown-cart-header">
            <span>{{ $wishlists->count() }} Items</span>
            <a href="{{ route('wishlist.index') }}">View Wishlist</a>
        </div>
        <ul class="shopping-list">
            @foreach($wishlists as $wishlist)
            <li>
                <a href="javascript:void(0)" class="remove" title="Remove this item"><i class="lni lni-close"></i></a>
                <div class="cart-img-head">
                    <a class="cart-img" href="{{ route('products.show', $wishlist->product->slug) }}">
                        <img src="{{ $wishlist->product->image_url }}" alt="#"></a>
                </div>
                <div class="content">
                    <h4><a href="product-details.html">{{ $wishlist->product->name }}</a></h4>
                    <p class="quantity"> <span class="amount">{{ Currency::format($wishlist->product->price) }}</span></p>
                </div>
            </li>
            @endforeach
        </ul>

    </div>
    <!--/ End Shopping Item -->
</div>
