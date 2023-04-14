<x-front-layout title="Products">

    @push('styles')
        <style>
            .wishlist.active .lni-heart {
                color: red;
            }
        </style>
    @endpush

    {{-- <x-slot:breadcrumb> --}}
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="breadcrumbs-content">
                        {{-- <h1 class="page-title">{{ $product->name_translate }}</h1> --}}
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                    <ul class="breadcrumb-nav">
                        <li><a href="{{ route('home') }}"><i class="lni lni-home"></i>{{ __('Home') }}</a></li>
                        <li><a href="{{ route('products.index') }}">{{ __('products') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    {{-- </x-slot:breadcrumb> --}}



    <section class="product-grids section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-12">

                    <div class="product-sidebar">

                        <form action="{{ URL::current() }}" method="get">

                            <div class="single-widget ">


                                <div class=" search">
                                    <h3>{{ __('Filter Products') }}</h3>
                                    <input name="search" class="form-control" type="text"
                                        value="{{ request()->query('search') ?? '' }}"
                                        placeholder="{{ __('search') }}">
                                </div>
                                <br>

                                <div class="product-sorting">
                                    <select name="sorting" class="form-control ">
                                        <option value="">{{ __('Sort by') }}:</option>
                                        <option value="a_z" @selected(request('sorting') == 'a_z')>{{ __('a_z') }}</option>
                                        <option value="z_a" @selected(request('sorting') == 'z_a')>{{ __('z_a') }}
                                        </option>
                                        <option value="high_low" @selected(request('sorting') == 'high_low')>{{ __('high_low') }}
                                        </option>
                                        <option value="low_high" @selected(request('sorting') == 'low_high')>{{ __('low_high') }}
                                        </option>
                                    </select>
                                </div>

                                <br>
                                <h3>{{ __('All Categories') }}</h3>

                                <div id="filter-categories-container">
                                    <ul class="list">
                                        @foreach ($categories as $category)
                                            <li>
                                                <input class="form-check-input" type="checkbox" name="categories[]"
                                                    value="{{ $category->id }}" @checked(in_array($category->id, old('categories', $categories_selected ?? [])))>

                                                <label class="form-check-label">
                                                    {{ $category->name_translate }}
                                                    <span>({{ $category->products_count }})</span>

                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <br>

                                <h3>{{ __('filter_by_price') }}</h3>
                                <div id="filter-price-container">
                                    <ul class="list">
                                        @foreach ($price_ranges as $filter_price)
                                            <li>
                                                <input class="form-check-input" type="checkbox" name="price_ranges[]"
                                                    value="{{ $filter_price->price_range }}"
                                                    @checked(in_array($filter_price->price_range, old('price_ranges', $price_ranges_selected ?? [])))>

                                                <label class="form-check-label">
                                                    {{ $filter_price->price_range }}
                                                    <span>({{ $filter_price->products_count }})</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <br>

                                <button class="btn btn-dark mx-2">{{ __('filter_button') }}</button>

                            </div>

                        </form>
                    </div>

                </div>


                <div class="col-lg-9 col-12">
                    <div class="product-grids-head">
                        <div class="product-grid-topbar">
                            <div class="row ">
                                <div class="col-8 md-4 ">

                                </div>
                                <div class="col-4 md-4">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <button class="nav-link active" id="nav-grid-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-grid" type="button" role="tab"
                                                aria-controls="nav-grid" aria-selected="true"><i
                                                    class="lni lni-grid-alt"></i></button>
                                            <button class="nav-link" id="nav-list-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-list" type="button" role="tab"
                                                aria-controls="nav-list" aria-selected="false"><i
                                                    class="lni lni-list"></i></button>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-grid" role="tabpanel"
                                aria-labelledby="nav-grid-tab">
                                <div class="row">

                                    {{-- <span class="sale-tag">-25%</span> --}}
                                    @foreach ($products as $product)
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="single-product">
                                                <div class="product-image">
                                                    <img data-pagespeed-high-res-src="{{ $product->image_url }}"
                                                        alt="#" src="{{ $product->image_url }}"
                                                        onload="pagespeed.switchToHighResAndMaybeBeacon(this);"
                                                        onerror="this.onerror=null;pagespeed.switchToHighResAndMaybeBeacon(this);">
                                                    <div class="button">
                                                        <a href="{{ route('products.show', $product->slug) }}"
                                                            class="btn"><i class="lni lni-cart"></i> Add to Cart</a>
                                                    </div>
                                                </div>
                                                <div class="product-info">
                                                    <span
                                                        class="category">{{ $product->category->name_translate }}</span>
                                                    <h4 class="title">
                                                        <a
                                                            href="{{ route('products.show', $product->slug) }}">{{ $product->name_translate }}</a>
                                                    </h4>
                                                
                                                    <div class="wishlist  {{$product->wishlists->first() != null ? 'active' : '' }} "  >
                                                        <a  href="javascript:void(0)" data-id="{{ $product->id }}">
                                                            <i class="lni lni-heart"></i>
                                                        </a>
                                                    </div>



                                                    {{-- <ul class="review">
                                                        <li><i class="lni lni-star-filled"></i></li>
                                                        <li><i class="lni lni-star-filled"></i></li>
                                                        <li><i class="lni lni-star-filled"></i></li>
                                                        <li><i class="lni lni-star-filled"></i></li>
                                                        <li><i class="lni lni-star"></i></li>
                                                        <li><span>4.0 Review(s)</span></li>
                                                    </ul> --}}

                                                    <div class="price">
                                                        <span> {{ Currency::format($product->price) }}</span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach


                                </div>


                                <div class="row">
                                    <div class="col-12">
                                        <div class="pagination left">
                                            <ul class="pagination-list">
                                                {{ $products->withQueryString()->links() }}
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>

    </section>


</x-front-layout>
