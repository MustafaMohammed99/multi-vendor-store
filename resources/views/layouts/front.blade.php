<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">



<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>{{ $title }}</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.svg') }}" />

    <!-- ========================= CSS here ========================= -->


    <link rel="stylesheet"
        href="{{ asset('assets/css/' . LaravelLocalization::getCurrentLocaleDirection() . '/bootstrap.min.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/css/' . LaravelLocalization::getCurrentLocaleDirection() . '/LineIcons.3.0.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/css/' . LaravelLocalization::getCurrentLocaleDirection() . '/tiny-slider.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/css/' . LaravelLocalization::getCurrentLocaleDirection() . '/glightbox.min.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/css/' . LaravelLocalization::getCurrentLocaleDirection() . '/main.css') }}" />
    @stack('styles')
</head>

<body>
    <!--[if lte IE 9]>
      <p class="browserupgrade">
        You are using an <strong>outdated</strong> browser. Please
        <a href="https://browsehappy.com/">upgrade your browser</a> to improve
        your experience and security.
      </p>
    <![endif]-->

    <!-- Preloader -->
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- /End Preloader -->

    <!-- Start Header Area -->
    <header class="header navbar-area">
        <!-- Start Topbar -->
        <div class="topbar">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="top-left">
                            <ul class="menu-top-link">
                                <li>
                                    <div class="select-position">
                                        <form action="{{ route('currency.store') }}" method="post">
                                            @csrf
                                            <select name="currency_code" onchange="this.form.submit()">
                                                <option value="USD" @selected('USD' == session('currency_code'))>$ USD</option>
                                                <option value="ILS" @selected('ILS' == session('currency_code'))>₪ ILS</option>
                                                {{-- <option value="EUR" @selected('EUR' == session('currency_code'))>€ EURO</option>
                                                <option value="JOD" @selected('JOD' == session('currency_code'))>₹ JOD</option>
                                                <option value="SAR" @selected('SAR' == session('currency_code'))>¥ SAR</option>
                                                <option value="QAR" @selected('QAR' == session('currency_code'))>৳ QAR</option> --}}
                                            </select>
                                        </form>
                                    </div>
                                </li>
                                <li>
                                    <div class="select-position">
                                        <select onchange="window.location.href=this.value">
                                            @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                <option
                                                    value="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                                    {{ app()->getLocale() === $localeCode ? 'selected' : '' }}>
                                                    {{ $properties['native'] }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="top-middle">
                            <ul class="useful-links">
                                <li><a href="{{ route('home') }}">{{ trans('Home') }}</a></li>
                                <li><a href="{{ route('products.index') }}">{{ trans('products') }}</a></li>
                                <li><a href="javascript:void(0)">@lang('About Us')</a></li>
                                <li><a href="javascript:void(0)">{{ __('Contact Us') }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="top-end">
                            @auth
                                <div class="user">
                                    <i class="lni lni-user"></i>
                                    {{ Auth::user()->name }}
                                </div>
                                <ul class="user-login">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout').submit()">Sign
                                            Out</a>
                                    </li>
                                    <form action="{{ route('logout') }}" id="logout" method="post"
                                        style="display:none">
                                        @csrf
                                    </form>
                                </ul>
                            @else
                                <div class="user">
                                    <i class="lni lni-user"></i>
                                    {{ __('Guest') }}
                                </div>
                                <ul class="user-login">
                                    <li>
                                        <a href="{{ route('login') }}">{{ Lang::get('Sign In') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                </ul>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Topbar -->
        <!-- Start Header Middle -->
        <div class="header-middle">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-3 col-7">
                        <!-- Start Header Logo -->
                        <a class="navbar-brand" href="index.html">
                            <img src="{{ asset('assets/images/logo/logo.svg') }}" alt="{{ __('Logo') }}">
                        </a>
                        <!-- End Header Logo -->
                    </div>
                    <div class="col-lg-5 col-md-7 d-xs-none">
                        <!-- Start Main Menu Search -->
                        <div class="main-menu-search">
                            <!-- navbar search start -->
                            <form action="{{ route('products.index') }}" method="get">

                                <div class="navbar-search search-style-5">
                                    <div class="search-input">
                                        <input name="search" class="form-control" type="text"
                                            value="{{ request()->query('search') ?? '' }}"
                                            placeholder="{{ __('Search Product Here...') }}">
                                    </div>
                                    <div class="search-btn">
                                        <button><i class="lni lni-search-alt"></i></button>
                                    </div>

                                </div>
                            </form>
                            <!-- navbar search Ends -->
                        </div>
                        <!-- End Main Menu Search -->
                    </div>
                    <div class="col-lg-4 col-md-2 col-5">
                        <div class="middle-right-area">
                            <div class="nav-hotline">
                                <i class="lni lni-phone"></i>
                                <h3>{{ __('Hotline') }}:
                                    <span>(+100) 123 456 7890</span>
                                </h3>
                            </div>

                            <div class="navbar-cart">
                                {{-- <div class="wishlist">
                                    <a href="javascript:void(0)">
                                        <i class="lni lni-heart"></i>
                                        <span class="total-items">0</span>
                                    </a>
                                </div> --}}
                                <div>
                                    <x-cart-menu />
                                </div>
                                <div>
                                    <x-wishlist-menu />
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Header Middle -->
        <!-- Start Header Bottom -->
        <div class="container">
            <div class="row align-items-center my-3">
                <div class="col-lg-8 col-md-6 col-12">
                    <div class="nav-inner">
                        <!-- Start Mega Category Menu -->
                        <div class="mega-category-menu">
                            <span class="cat-button"><i class="lni lni-menu"></i>{{ __('All Categories') }}</span>
                            <ul class="sub-category">
                                @foreach ($categories as $category)
                                    <li>
                                        <a href="{{ route('products.index', ['categories[]' => $category->id]) }}">
                                            {{ $category->name_translate }}
                                            <i class="{{ $category->children->count() > 0 ? (LaravelLocalization::getCurrentLocaleDirection() === 'ltr' ? 'lni lni-chevron-right' : 'lni lni-chevron-left') : '' }}"></i>
                                        </a>
                                        @if ($category->children->count() > 0)
                                            <ul class="inner-sub-category my-1">
                                                @foreach ($category->children as $subcategory)
                                                    <li>
                                                        <a href="{{ route('products.index', ['categories[]' => $subcategory->id]) }}">
                                                            {{ $subcategory->name_translate }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- End Mega Category Menu -->
                        <!-- Start Navbar -->

                        <!-- End Navbar -->
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Nav Social -->
                    <div class="nav-social">
                        <h5 class="title">{{ __('Follow Us') }}:</h5>
                        <ul>
                            <li>
                                <a href="javascript:void(0)"><i class="lni lni-facebook-filled"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"><i class="lni lni-twitter-original"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"><i class="lni lni-instagram"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"><i class="lni lni-skype"></i></a>
                            </li>
                        </ul>
                    </div>
                    <!-- End Nav Social -->
                </div>
            </div>
          </div>
          <!-- End Header Bottom -->
    </header>
    <!-- End Header Area -->

    <!-- Start Breadcrumbs -->
    {{ $breadcrumb ?? '' }}
    <!-- End Breadcrumbs -->

    {{ $slot }}

    <!-- Start Footer Area -->
    <footer class="footer">
        <!-- Start Footer Top -->
        <div class="footer-top">
            <div class="container">
                <div class="inner-content">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-12">
                            <div class="footer-logo">
                                <a href="index.html">
                                    <img src="{{ asset('assets/images/logo/white-logo.svg') }}" alt="{{ __('Logo') }}">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 col-12">
                            <div class="footer-newsletter">
                                <h4 class="title">
                                    {{ __('Subscribe to our Newsletter') }}
                                    <span>{{ __('Get all the latest information, Sales and Offers.') }}</span>
                                </h4>
                                <div class="newsletter-form-head">
                                    <form action="#" method="get" target="_blank" class="newsletter-form">
                                        <input name="EMAIL" placeholder="{{ __('Email address here...') }}" type="email">
                                        <div class="button">
                                            <button class="btn">{{ __('Subscribe') }}<span class="dir-part"></span></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Footer Top -->
        <!-- Start Footer Middle -->
        <div class="footer-middle">
            <div class="container">
                <div class="bottom-inner">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-12">
                            <!-- Single Widget -->
                            <div class="single-footer f-contact">
                                <h3>{{__('Get In Touch With Us')}}</h3>
                                <p class="phone">{{__('Phone')}}: +1 (900) 33 169 7720</p>
                                <ul>
                                    <li><span>{{__('Monday-Friday')}}: </span> 9.00 am - 8.00 pm</li>
                                    <li><span>{{__('Saturday')}}: </span> 10.00 am - 6.00 pm</li>
                                </ul>
                                <p class="mail">
                                    <a href="mailto:support@shopgrids.com">{{__('support@shopgrids.com')}}</a>
                                </p>
                            </div>
                            <!-- End Single Widget -->
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <!-- Single Widget -->
                            <div class="single-footer our-app">
                                <h3>{{__('Our Mobile App')}}</h3>
                                <ul class="app-btn">
                                    <li>
                                        <a href="javascript:void(0)">
                                            <i class="lni lni-apple"></i>
                                            <span class="small-title">{{__('Download on the')}}</span>
                                            <span class="big-title">{{__('App Store')}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">
                                            <i class="lni lni-play-store"></i>
                                            <span class="small-title">{{__('Download on the')}}</span>
                                            <span class="big-title">{{__('Google Play')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- End Single Widget -->
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <!-- Single Widget -->
                            <div class="single-footer f-link">
                                <h3>{{ __('Information') }}</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">{{ __('About Us') }}</a></li>
                                    <li><a href="javascript:void(0)">{{ __('Contact Us') }}</a></li>
                                    <li><a href="javascript:void(0)">{{ __('Downloads') }}</a></li>
                                    <li><a href="javascript:void(0)">{{ __('Sitemap') }}</a></li>
                                    <li><a href="javascript:void(0)">{{ __('FAQs Page') }}</a></li>
                                </ul>
                            </div>
                            <!-- End Single Widget -->
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <!-- Single Widget -->
                            <div class="single-footer f-link">
                                <h3>{{ __('Shop Departments') }}</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">{{ __('Computers & Accessories') }}</a></li>
                                    <li><a href="javascript:void(0)">{{ __('Smartphones & Tablets') }}</a></li>
                                    <li><a href="javascript:void(0)">{{ __('TV, Video & Audio') }}</a></li>
                                    <li><a href="javascript:void(0)">{{ __('Cameras, Photo & Video') }}</a></li>
                                    <li><a href="javascript:void(0)">{{ __('Headphones') }}</a></li>
                                </ul>
                            </div>
                            <!-- End Single Widget -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Middle -->
        <!-- Start Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="inner-content">
                    <div class="row align-items-center">
                        <div class="col-lg-4 col-12">
                            <div class="payment-gateway">
                                <span>We Accept:</span>
                                <img src="{{ asset('assets/images/footer/credit-cards-footer.png') }}"
                                    alt="#">
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="copyright">
                                <p>Designed and Developed by<a href="https://graygrids.com/" rel="nofollow"
                                        target="_blank">GrayGrids</a></p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <ul class="socila">
                                <li>
                                    <span>Follow Us On:</span>
                                </li>
                                <li><a href="javascript:void(0)"><i class="lni lni-facebook-filled"></i></a></li>
                                <li><a href="javascript:void(0)"><i class="lni lni-twitter-original"></i></a></li>
                                <li><a href="javascript:void(0)"><i class="lni lni-instagram"></i></a></li>
                                <li><a href="javascript:void(0)"><i class="lni lni-google"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Bottom -->
    </footer>
    <!--/ End Footer Area -->

    <!-- ========================= scroll-top ========================= -->
    <a href="#" class="scroll-top">
        <i class="lni lni-chevron-up"></i>
    </a>

    <!-- ========================= JS here ========================= -->
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        const userId = "{{ Auth::user()->id ?? Session::getId() }}"
    </script>

    <script>
        const csrf_token = "{{ csrf_token() }}";
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/wishlist.js') }}"></script>

    {{-- @vite(['resources/js/pusher.js']) --}}

    <script src="{{ asset('js/js/pusher.js') }}"></script>

    @stack('scripts')

</body>

</html>
