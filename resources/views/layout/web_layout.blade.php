<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Restaurant</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset("web/assets/images/favicon.ico") }}" />
    <link rel="stylesheet" href="{{ asset("web/assets/css/vendor/font-awesome.min.css") }}" />
    <link rel="stylesheet" href="{{ asset("web/assets/css/vendor/Pe-icon-7-stroke.css") }}" />
    <link rel="stylesheet" href="{{ asset("web/assets/css/plugins/animate.min.css") }}">
    <link rel="stylesheet" href="{{ asset("web/assets/css/plugins/jquery-ui.min.css") }}">
    <link rel="stylesheet" href="{{ asset("web/assets/css/plugins/swiper-bundle.min.css") }}">
    <link rel="stylesheet" href="{{ asset("web/assets/css/plugins/nice-select.css") }}">
    <link rel="stylesheet" href="{{ asset("web/assets/css/plugins/magnific-popup.min.css") }}" />
    <link rel="stylesheet" href="{{ asset("web/assets/css/style.css") }}">
</head>

<body>
    <div class="main-wrapper">
        <header class="main-header_area position-relative">
            <div class="header-middle py-5">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="header-middle-wrap">
                                <a href="#" class="header-logo">
                                    <h3 style="color: #bac34e">Restaurant</h3>
                                </a>
                                <div class="header-search-area d-none d-lg-block">
                                    <form action="#" class="header-searchbox">
                                        <input class="input-field" type="text" placeholder="Tìm kiếm món ăn">
                                        <button class="btn btn-outline-whisper btn-primary-hover" type="submit"><i class="pe-7s-search"></i></button>
                                    </form>
                                </div>
                                <div class="header-right">
                                    <ul>
                                        <li class="dropdown d-none d-md-block">
                                            <button class="btn btn-link dropdown-toggle ht-btn p-0" type="button" id="settingButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="pe-7s-users"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingButton">
                                                <li><a class="dropdown-item" href="my-account.html">Đăng nhập</a></li>
                                                <li><a class="dropdown-item" href="login-register.html">Đăng ký</a></li>
                                            </ul>
                                        </li>
                                        <li class="d-block d-lg-none">
                                            <a href="#searchBar" class="search-btn toolbar-btn">
                                                <i class="pe-7s-search"></i>
                                            </a>
                                        </li>
                                        <li class="minicart-wrap me-3 me-lg-0">
                                            <a href="#miniCart" class="minicart-btn toolbar-btn">
                                                <i class="pe-7s-shopbag"></i>
                                                <span class="quantity">3</span>
                                            </a>
                                        </li>
                                        <li class="mobile-menu_wrap d-block d-lg-none">
                                            <a href="#mobileMenu" class="mobile-menu_btn toolbar-btn pl-0">
                                                <i class="pe-7s-menu"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layout.partial.menu')

            @include('layout.partial.mini-cart')

            <div class="global-overlay"></div>
        </header>
        @yield('content')

        @include('layout.partial.footer')
        
        <a class="scroll-to-top" href="">
            <i class="fa fa-chevron-up"></i>
        </a>
    </div>

    <script src="{{ asset("web/assets/js/vendor/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/vendor/jquery-3.5.1.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/vendor/jquery-migrate-3.3.0.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/vendor/modernizr-3.11.2.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/vendor/jquery.waypoints.js") }}"></script>
    <script src="{{ asset("web/assets/js/plugins/wow.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/plugins/jquery-ui.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/plugins/swiper-bundle.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/plugins/jquery.nice-select.js") }}"></script>
    <script src="{{ asset("web/assets/js/plugins/parallax.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/plugins/jquery.magnific-popup.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/main.js") }}"></script>
    @yield('scripts')
</body>
</html>